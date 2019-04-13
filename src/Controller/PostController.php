<?php

namespace App\Controller;

use App\Entity\Post;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index()
    {
        $rep = $this->getDoctrine()->getRepository(Post::class);
        $posts = $rep->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post/top", name="post_top")
     */
    public function top()
    {
        $sort = 'desc';
        if(isset($_GET["sort"]) && in_array($_GET['sort'], ["asc", "desc"])){
            $sort = $_GET["sort"];
        }

        $rep = $this->getDoctrine()->getRepository(Post::class);
        $posts = $rep->findBy([],['rating' => $sort]);

        return $this->render('post/top.html.twig', [
            'posts' => $posts,
            'sort' => $sort,
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_info", requirements={"id"="\d+"})
     * @param $id
     * @return Response
     */
    public function get($id)
    {
        $rep = $this->getDoctrine()->getRepository(Post::class);
        $post = $rep->find($id);

        return $this->render('post/post.html.twig', [
            'id' => $post->getId(),
            'photo' => $post->getPhoto(),
            'title' => $post->getTitle(),
            'text' => $post->getText(),
            'rating' => $post->getRating(),
            'canVote' => !isset($_SESSION["voted"]) || !in_array($id, $_SESSION["voted"]),
        ]);
    }

    /**
     * @Route("/post/{id}/artem", name="post_delete", requirements={"id"="\d+"})
     * @param $id
     * @return Response
     */
    public function deletePost($id)
    {
        $rep = $this->getDoctrine()->getRepository(Post::class);
        $post = $rep->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();


        return $this->render('post/custom.html.twig', ['message' => 'Post deleted',]);
//        return new Response('Post deleted');

    }

    /**
     * @Route("/post/new", name="post_new")
     */
    public function add()
    {
        return $this->render('post/new.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    /**
     * @Route("/post/create", name="post_create")
     */
    public function create(Request $request)
    {
        $post = new Post();

        $form = $this->createFormBuilder($post)
            ->add("title", TextType::class)
            ->add("photo", TextType::class)
            ->add("text", TextType::class)
            ->add("safe", SubmitType::class, ["label" => "create new post"], ["href" => 'post/custom.html.twig'])
            ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $post = $form->getData();
                $post->setCreatedAt(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($post);
                $entityManager->flush();
            }


        return $this->render('post/create.html.twig', ['form' => $form->createView()]);

//        $entityManager = $this->getDoctrine()->getManager();
//
//        $post = new Post();
//        $post->setTitle($_POST["title"]);
//        $post->setPhoto($_POST["photo"]);
//        $post->setText($_POST["text"]);
//        $post->setRating(0);
//
//
//
//        $entityManager->persist($post);
//        $entityManager->flush();
//
//        return $this->render('post/custom.html.twig', ['message' => 'Saved new post with id ' . $post->getId(),]);

//        return new Response('Saved new post with id '.$post->getId());

    }

    /**
     * @Route("/post/vote", name="post_vote")
     */
    public function vote()
    {
        $post_id = $_POST["id"];
        if (isset($_SESSION["voted"]) && in_array($post_id, $_SESSION["voted"])) {
            return $this->render('post/custom.html.twig', ['message' => 'You already voted',]);
        }
        $isLike = $_POST["type"] == "1";
        $diff = $isLike ? 1 : -1;

        $rep = $this->getDoctrine()->getRepository(Post::class);
        $entityManager = $this->getDoctrine()->getManager();
        $post = $rep->find($post_id);
        $post->setRating($post->getRating() + $diff);
        $entityManager->persist($post);
        $entityManager->flush();

        if (!isset($_SESSION["voted"])) {
            $_SESSION["voted"] = array();
        }
        $_SESSION["voted"][] = $post_id;

        return $this->render('post/custom.html.twig', ['message' => 'Thanks for vote',]);
    }

    /**
     * @Route("/post/user", name="post_user")
     */

    public function user(){
        return $this->render('post/new.html.twig', [
        ]);
    }
}

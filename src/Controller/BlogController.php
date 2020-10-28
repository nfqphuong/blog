<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", defaults={"page": "1"}, methods="GET", name="blog_index")
     * @Route("/page/{page<[1-9]\d*>}", methods="GET", name="blog_index_paginated")
     */
    public function index(Request $request, int $page, PostRepository $posts): Response
    {
        $data = $posts->findLatest($page);
        $data["currentPage"] = $page;
        return $this->render('blog/index.html.twig', $data);
    }

    /**
     * @Route("/post/{id}", methods="GET", name="blog_post")
     */
    public function postShow(Request $request ,int $id, PostRepository $posts): Response
    {
        return $this->render('blog/post.html.twig', [
            'post'=> $posts->findByID($id),
        ]);
    }
}

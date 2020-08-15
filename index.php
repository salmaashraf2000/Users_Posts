
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
         require_once 'Post.php';
         $post= new Post();
         $result=$post->CheckPosts();
         $post->Mail($result);
        ?>
    </body>
</html>

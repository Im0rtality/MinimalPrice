<!DOCTYPE html>
<html>
    <head>
        <link href="../css/docs.css" rel="stylesheet" type="text/css">
        <link href="../css/prettify.css" rel="stylesheet" type="text/css">
        <link href="../css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="../css/bootstrap-responsive.css" rel="stylesheet" type="text/css">
        <title><?php echo $template['title']; ?></title>
    </head>
    <body>
        <header></header>
        <div class="container">
<!-- Docs nav
    ================================================== -->
            <div class="row">
                <div class="span3 bs-docs-sidebar">
                    <ul class="nav bs-docs-sidenav nav-stacked">
                        <?php foreach($template['nav'] as $nav): ?>
                        <li>
                            <a href="?page=<?php echo $nav['link']; ?>"><?php echo $nav['name']; ?></a>
                        </li>
                        <?php endforeach; ?>                
                    </ul>
                </div>
                <div class="span9">
                    <?php echo $template['body']; ?>
                </div>
            </div>
        </div>

<!-- Footer
    ================================================== -->
        <footer></footer>
    </body>
</html>
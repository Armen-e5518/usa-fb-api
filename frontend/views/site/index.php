<?php

/* @var $this yii\web\View */

$this->title = 'Facebook Posts';
?>


<div class="container">
    <form action="" class="form" method="post">
        <div class="form-group">
            <label for="usr">Facebook page url:</label>
            <div class="row">
                <div class="col-md-12">
                    <input type="text" value="<?= $url ?>" name="page_url" class="form-control" id="usr">
                </div>
                <div class="col-md-12 button">
                    <span class="time">
                        <?= $time ?>
                    <span>ms</span>
                    </span>
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="submit" name="cache" value="1" class="btn btn-success">UPDATE NOW</button>
                </div>
            </div>
        </div>
    </form>
    <div class="content">
        <hr>
        <div class="row">
            <img width="100%" src="<?= !empty($page['cover']['source']) ? $page['cover']['source'] : '' ?>" alt="">
        </div>
        <br>
        <div class="row page">
            <div class="col-md-4">
                <img width="150px"
                     src="<?= !empty($page['photos']['data'][0]['picture']) ? $page['photos']['data'][0]['picture'] : '' ?>"
                     alt="">
            </div>
            <div class="col-md-6">
                <h1 class="title"><?= !empty($page['phone']) ? $page['phone'] : '' ?> </h1>
                <hr>
                <p class="description-title">Description:</p>
                <p class="description">
                    <?= !empty($page['description']) ? $page['description'] : '' ?>
                </p>
            </div>
        </div>
        <hr class="posts-hr">
        <div class="posts">
            <?php if (!empty($posts['data'])): ?>
                <?php foreach ($posts['data'] as $data): ?>
                    <div class="row">
                        <h1 class="title">
                            <?= !empty($data['created_time']) ? $data['created_time'] : '' ?>
                        </h1>
                        <div class="col-md-4">
                            <?php if (
                                !empty($data['attachments']['data'][0]['type']) &&
                                $data['attachments']['data'][0]['type'] == 'video_inline' &&
                                !empty($data['attachments']['data'][0]['url'])
                            ): ?>
                                <div class="fb-video" data-href="<?= $data['attachments']['data'][0]['url'] ?>"
                                     data-width="300" data-show-text="false">
                                    <div class="fb-xfbml-parse-ignore">
                                        <blockquote cite="<?= $data['attachments']['data'][0]['url'] ?>"></blockquote>
                                    </div>
                                </div>
                            <?php elseif (!empty($data['attachments']['data'][0]['media']['image']['src'])): ?>
                                <img width="300px" src="<?= $data['attachments']['data'][0]['media']['image']['src'] ?>"
                                     alt="">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                        <textarea name="" id="" cols="60" rows="10">
                            <?= !empty($data['message']) ? $data['message'] : '' ?>
                        </textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
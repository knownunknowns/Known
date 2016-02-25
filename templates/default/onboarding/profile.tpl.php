<div id="form-main">
    <div id="form-div">
        <h2 class="profile">Create your page</h2>

        <?= $this->draw('shell/simple/messages') ?>

        <form action="<?= $vars['user']->getDisplayURL() ?>" method="post" enctype="multipart/form-data">

            <p class="profile-pic" id="photo-preview">
                <img src="<?= $vars['user']->getIcon() ?>" alt="" style="width: 300px;height:150px; cursor: pointer"
                     class="icon-container" onclick="$('#photo').click();"/>
            </p>

            <div class="upload">
                <span class="camera btn-file" type="button" value="Add a photo of your project">
                    <span id="photo-filename">Add a photo of YOUR PROJECT (not your face)</span>
                    <span >It should be at least 1000 pixels wide by 500 high, and in a 2:1 width:hight ratio<span>
                    <input type="file" name="avatar" id="photo" class="col-md-9" accept="image/*;capture=camera"
                           onchange="photoPreview(this)"/>
                </span>
            </div>
            <p class="name">
                <label class="control-label" for="inputName">Your name<br/></label>
                <input name="name" type="text" class="profile-input" placeholder="Ben Franklin" id="name"/>
            </p>

            <p class="projTitle">
                <label class="control-label" for="handle">Your project title<br/></label>
                <input name="projTitle" type="text" class="profile-input" placeholder="Can i watch netflix for 6 months, document it, and get a first?" id="projTitle"/>
            </p>

            <p class="text">
                <label class="control-label" for="inputName">Your project description(about 100 words)<br/></label>
                <textarea name="profile[description]" class="profile-input" id="description"
                          placeholder="In this project, I attempted to watch netflix for six months in an exhaustivley documented manner, with the aim of understanding the interplay between..."></textarea>
            </p>

            <p class="website">
                <span id="websites">
                    <label class="control-label" for="inputWebsite">Your other websites
                        <small>(a blog, a portfolio, Twitter, Facebook, etc)</small>
                        <br/></label>
                    <input name="profile[url][]" type="text" class="profile-input" id="website"
                           placeholder="http://..."/>
                </span>
                <a href="#" onclick="$('#websites').append($('#website-template').html()); return false;">Add another
                    website</a>
            </p>
            <div class="col-md-12">
                <div class="submit">
                    <?= \Idno\Core\Idno::site()->actions()->signForm('/profile/' . $vars['user']->getHandle()) ?>
                    <input type="submit" value="Save profile" class="btn btn-primary btn-lg btn-responsive">
                    <input type="hidden" name="onboarding" value="1"/>
                </div>
        </form>
        <?php $vars['user']->setTitle("drcable")?>
        <?=implode("<br />",
            array_map(
                function ($v, $k) { return $k . '=' . $v; }, 
                $vars['user']->attributes ,
                array_keys($vars['user']->attributes )
            )
        )?>
        <div id="website-template" style="display:none"><input name="profile[url][]" type="text" class="profile-input"
                                                               id="website" placeholder="http://..."/></div>

    </div>
</div>

<script>
    //if (typeof photoPreview !== function) {
    function photoPreview(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#photo-preview').html('<img src="" id="photopreview" style="display:none; width: 150px">');
                $('#photo-filename').html('Choose different photo');
                $('#photopreview').attr('src', e.target.result);
                $('#photopreview').show();
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    //}
</script>

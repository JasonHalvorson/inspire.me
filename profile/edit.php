<?php require_once($_SERVER["DOCUMENT_ROOT"]."/includes/header.php"); ?>

<?php
// SECTION user query
$user_id = (isset($_GET["user_id"])) ? $_GET["user_id"] : $_SESSION["user_id"];

$user_query = " SELECT users.*, avatars.url AS avatar, banners.url AS banner
                FROM users 
                LEFT JOIN avatars
                ON users.avatar_id = avatars.id
                LEFT JOIN banners
                ON users.banner_id = banners.id
                WHERE users.id = " . $user_id;

$tag_query  = " SELECT user_tags.*, tags.* FROM user_tags
                LEFT JOIN tags
                ON user_tags.tag_id = tags.id
                WHERE user_tags.user_id = $user_id";

if ($user_request = mysqli_query($conn, $user_query)):
  while ($user_row = mysqli_fetch_array($user_request)):

    $tags = [];
    $tag_ids = [];
    
    if ($tag_request = mysqli_query($conn, $tag_query)) {
      while ($tag_row = mysqli_fetch_array($tag_request)) {
        $tags[] = $tag_row["name"];
        $tag_ids[] = $tag_row["id"];
      }
    }

        $username = $user_row["username"];
        $email    = $user_row["email"];
        $bio      = $user_row["bio"];
        $avatar   = $user_row["avatar"];
        $banner   = $user_row["banner"];
// !SECTION user query
?>

<body class="">
  <div class="wrapper">
    <?php require_once($_SERVER["DOCUMENT_ROOT"]."/includes/sidebar.php"); ?>
    <div class="main-panel">
      <?php require_once($_SERVER["DOCUMENT_ROOT"]."/includes/navbar.php"); ?>

      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-8">
              <div class="card">
                <div class="card-header card-header-icon card-header-rose">
                  <div class="card-icon">
                    <i class="material-icons">perm_identity</i>
                  </div>
                  <h4 class="card-title">Edit Profile</h4>
                </div>
                <div class="card-body">
                  <!-- SECTION edit form -->
                  <form action="/actions/edit_profile.php" method="POST" enctype="multipart/form-data" id="edit">
                    <input type="hidden" name="user_id" value="<?=$user_row["id"];?>">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating" for="username">Username</label>
                          <input type="text" class="form-control" id="username" name="username" value="<?=$username?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating" for="email">Email address</label>
                          <input type="email" class="form-control" id="email" name="email" value="<?=$email?>">
                        </div>
                      </div>
                    </div>
                    <div class="row mt-3">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating" for="bio">About Me</label>
                          <textarea class="form-control" rows="5" id="bio" name="bio" ><?=$bio?></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="mr-3">I am a:</label>
                          <div class="form-check form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="tags[]" value="1" <?=(in_array(1, $tag_ids)) ? "checked" : false ?>> Web Developer
                              <span class="form-check-sign">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                          <div class="form-check form-check-inline">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="tags[]" value="2" <?=(in_array(2, $tag_ids)) ? "checked" : false ?>> Graphic Designer
                              <span class="form-check-sign">
                                  <span class="check"></span>
                              </span>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6 text-center my-auto">
                      <h4 class="title">Avatar</h4>
                      <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                        <div class="fileinput-new thumbnail img-circle">
                          <img src="<?=($avatar) ? $avatar : "/assets/img/placeholder.jpg"?>" style="width: 100px; height: 100px; object-fit: cover">
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
                        <div>
                          <span class="btn btn-round btn-primary btn-file">
                            <span class="fileinput-new">Add Photo</span>
                            <span class="fileinput-exists">Change</span>
                            <input type="file" name="avatar" />
                          </span>
                          <br />
                          <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 text-center my-auto">
                      <h4 class="title">Banner Image</h4>
                      <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                        <div class="fileinput-new thumbnail">
                          <img src="<?=($banner) ? $banner : "/assets/img/image_placeholder.jpg"?>">
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail"></div>
                        <div>
                          <span class="btn btn-primary btn-round btn-file">
                            <span class="fileinput-new">Select image</span>
                            <span class="fileinput-exists">Change</span>
                            <input type="file" name="banner" />
                          </span>
                          <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                        </div>
                      </div>
                    </div>
                  </div>
                    <button type="submit" class="btn btn-rose pull-right" name="action" value="update">Update Profile</button>
                    <div class="clearfix"></div>
                  </form>
                  <!-- !SECTION edit form -->
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <!-- SECTION preview card -->
              <div class="card card-profile">
                <?=($banner) ? "<img class=\"card-img-top\" src=\"$banner\">" : false ?>
                <div class="card-avatar">
                  <a href="#pablo">
                    <img class="img" src="<?=($avatar) ? $avatar : "/assets/img/placeholder.jpg"?>" alt="<?=$username?>'s Avatar"/>
                  </a>
                </div>

                <div class="card-body">
                  <h4 class="card-title"><?=$_SESSION["username"];?></h4>
                  <h6 class="card-category">
                    <?php require_once($_SERVER["DOCUMENT_ROOT"]."/includes/tags.php"); ?>
                  </h6>
                  <p class="card-description">
                    <?=$bio?>
                  </p>
                  <a href="#pablo" class="btn btn-rose btn-round">View Profile</a>
                </div>
              </div>
              <!-- !SECTION preview card -->
              <!-- ANCHOR change password and delete account -->
              <div class="card">
                <div class="card-header card-header-icon card-header-danger">
                  <div class="card-icon">
                    <i class="material-icons">warning</i>
                  </div>
                  <h4 class="card-title">Scary Buttons</h4>

                </div>
                <div class="card-body">
                  <button class="btn btn-warning" id="changePassword" data-toggle="modal" data-target="#changePasswordModal">Change Password</button>
                  <button class="btn btn-danger" id="delete">Delete Account</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<div class="modal fade" id="changePasswordModal" tabindex="-1" role="">
  <div class="modal-dialog modal-login" role="document">
      <div class="modal-content">
          <div class="card card-signup card-plain">
              <div class="modal-header">
                <div class="card-header card-header-primary text-center">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                  </button>

                  <h4 class="card-title">Change your Password</h4>
                </div>
              </div>
              <div class="modal-body px-3">
                  <form class="form" method="POST" action="/actions/edit_profile.php">
                      <input type="hidden" name="user_id" value="<?=$user_row["id"]?>">
                      <div class="card-body">

                          <div class="form-group bmd-form-group">
                            <label for="oldpassword" class="bmd-label-floating">Old Password</label>
                            <input id="oldpassword" name="oldpassword" type="password" class="form-control">
                          </div>

                          <div class="form-group bmd-form-group">
                              <label for="password" class="bmd-label-floating">New Password</label>
                              <input id="password" name="password" type="password" class="form-control">
                          </div>

                          <div class="form-group bmd-form-group">
                              <label for="password2" class="bmd-label-floating">Confirm New Password</label>
                              <input id="password2" name="password2" type="password" class="form-control">
                          </div>
                      </div>
                    <div class="modal-footer my-3 justify-content-center">
                        <button type="submit" name="action" value="change_password" class="btn btn-primary btn-wd">Change Password</button>
                    </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>

<?php 
  endwhile; 
endif; // User
?>

<?php require_once($_SERVER["DOCUMENT_ROOT"]."/includes/footer.php"); ?>
<?php require_once($_SERVER["DOCUMENT_ROOT"]."/includes/error_check.php"); ?>

<script>
// ANCHOR delete SWAL
$("button#delete").click(function (e) { 
  e.preventDefault();
  
  const deleteAccountSwal = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-danger',
      cancelButton: 'btn btn-secondary'
    },
    buttonsStyling: false
  })

  deleteAccountSwal.fire({
    title: 'Are you sure you would like to delete your account?',
    text: "This action is irreversible.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'No, cancel!',
    reverseButtons: true
  }).then((result) => {
    if (result.value) {
      deleteAccountSwal.fire({
        title: 'Are you completely sure you want to permanently delete your account?',
        text: 'If so, please type "delete" below.',
        icon: 'warning',
        input: 'text',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: 'Delete My Account',
        inputValidator: (value) => {
          if (value === "delete") {
            var user_id = $("input:hidden[name=user_id]").val();
            $.ajax({
              type: "POST",
              url: "/actions/edit_profile.php",
              data: {action: "delete", user_id: user_id}

            }).done(function() {
              window.location.href = "/";
            })
          } else {
            return 'You did not type "delete".'
          }
        }
      }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
          deleteAccountSwal.fire(
            'Cancelled',
            'Your account has not been deleted.',
            'error'
          )
        }
      })
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      deleteAccountSwal.fire(
        'Cancelled',
        'Your account has not been deleted.',
        'error'
      )
    }
  })

});
</script>
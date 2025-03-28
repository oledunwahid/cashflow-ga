<?php


$companylogin           = $rowlogin['company'];
$lokasilogin            = $rowlogin['lokasi'];
$departmentlogin        = $rowlogin['department'];
$sectionlogin           = $rowlogin['section'];
$positionlogin          = $rowlogin['position'];
$clasifikasilogin       = $rowlogin['clasifikasi'];
$atasanlogin            = $rowlogin['atasan'];
$pohlogin               = $rowlogin['poh'];
$rosterlogin            = $rowlogin['roster'];
$dohlogin               = $rowlogin['doh'];
$statuslogin            = $rowlogin['status'];

$sqlemail = mysqli_query($koneksi, "SELECT * FROM login  WHERE idnik='$idnik'");
$rowemail = mysqli_fetch_assoc($sqlemail);




date_default_timezone_set('Asia/Jakarta');

?>


<div class="profile-foreground position-relative mx-n4 mt-n4">
    <div class="profile-wid-bg">
        <img src="assets/images/profile-bg.jpg" alt="" class="profile-wid-img" />
    </div>
</div>
<div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
    <div class="row g-4">
        <div class="col-auto">
            <div class="avatar-lg">
                <img src="file/profile/<?= $foto_profile ?>" alt="user-img" class="avatar-lg img-thumbnail rounded-circle mx-auto profile-img" />
            </div>
        </div>
        <!--end col-->
        <div class="col">
            <div class="p-2">
                <h3 class="text-white mb-1"><?= $namalogin ?></h3>
                <p class="text-white-75"><?= $divisilogin ?> / <?= $positionlogin ?></p>

            </div>
        </div>
        <!--end col-->

        <!--end col-->

    </div>
    <!--end row-->
</div>

<div class="row">
    <div class="col-lg-12">
        <div>
            <div class="d-flex">
                <!-- Nav tabs -->
                <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                            <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Overview</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                            <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Team</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-14" data-bs-toggle="tab" href="#documents" role="tab">
                            <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Documents</span>
                        </a>
                    </li>
                </ul>

            </div>
            <!-- Tab panes -->
            <div class="tab-content pt-4 text-muted">
                <div class="tab-pane active" id="overview-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-xxl-3">


                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Info</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <th class="ps-0" scope="row">ID-NIK :</th>
                                                    <td class="text-muted"><?= $niklogin ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0" scope="row">Full Name :</th>
                                                    <td class="text-muted"><?= $namalogin ?></td>
                                                </tr>

                                                <tr>
                                                    <th class="ps-0" scope="row">E-mail :</th>
                                                    <td class="text-muted"><?= $rowemail['username']; ?></td>
                                                </tr>


                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
                            <div class="card">
                                <div class="card-body">

                                    <h5 class="card-title mb-3">Update Password</h5>
                                    <div class="p-2 mt-4">
									
									<form action="function/change_password.php" method="POST" >
                                            <input type="hidden" name="idnik" value="<?= $niklogin ?>">
									<div class="mb-3">
                                        
                                            <label class="form-label" for="password-input">Current Password</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" class="form-control pe-5 password-input" placeholder="Enter password"  name="currentPassword" required>
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                            </div>
                                        </div>
										<div class="mb-3">
                                            <lab el class="form-label" for="password-input">New Password</label>
                                            <div class="position-relative auth-pass-inputgroup">
                                                <input type="password" class="form-control pe-5 password-input" onpaste="return false" placeholder="Enter password" id="password-input" aria-describedby="passwordInput"  name="newPassword"  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                            </div>
                                            <div id="passwordInput" class="form-text">Must be at least 8 characters.</div>
                                        </div>
										<div class="mb-3">
                                             <label class="form-label" for="confirm-password-input">Confirm Password</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" class="form-control pe-5 password-input" onpaste="return false" placeholder="Confirm password" name="confirmPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" id="confirm-password-input" required>                                                
												<button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="confirm-password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                            </div>
											<div id="message"></div>
                                        </div>
										 <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                                            <h5 class="fs-13">Password must contain:</h5>
                                            <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8 characters</b></p>
                                            <p id="pass-lower" class="invalid fs-12 mb-2">At <b>lowercase</b> letter (a-z)</p>
                                            <p id="pass-upper" class="invalid fs-12 mb-2">At least <b>uppercase</b> letter (A-Z)</p>
                                            <p id="pass-number" class="invalid fs-12 mb-0">A least <b>number</b> (0-9)</p>
                                        </div>
										
									
									
									<div class="mt-4">
                                            <button class="btn btn-success w-100" name="update-user" type="submit">Update Password</button>
                                        </div>
										</form>
                                     </div>

                                </div><!-- end card body -->

                                
                            </div><!-- end card -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center">
                                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                            <img src="file/profile/<?= $foto_profile ?>" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image">
                                            <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                                <form method="POST" action="function/update_foto_profile.php" enctype="multipart/form-data">
                                                    <input type="text" value="<?= $niklogin ?>" name="idnik" hidden>
                                                    <input id="profile-img-file-input" type="file" name="file_foto" class="profile-img-file-input">
                                                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                                        <span class="avatar-title rounded-circle bg-light text-body">
                                                            <i class="ri-camera-fill"></i>
                                                        </span>
                                                    </label>
                                            </div>

                                        </div>



                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success" name="Update_Profile">Update Foto</button>
                                        </form>

                                    </div>

                                </div>
                            </div>

                        </div>

                        <!--end col-->
                        <div class="col-xxl-9">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Company</h5>


                                    <div class="row">
                                        <div class="col-6 col-md-4">
                                            <div class="d-flex mt-4">
                                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                        <i class="ri-user-2-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="mb-1">Company </p>
                                                    <h6 class="text-truncate mb-0"><?= $companylogin ?></h6>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-4">
                                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                        <i class="ri-user-2-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="mb-1">Lokasi </p>
                                                    <h6 class="text-truncate mb-0"><?= $lokasilogin ?></h6>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-4">
                                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                        <i class="ri-user-2-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="mb-1">Divisi </p>
                                                    <h6 class="text-truncate mb-0"><?= $divisilogin ?></h6>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-4">
                                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                        <i class="ri-user-2-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="mb-1">Department </p>
                                                    <h6 class="text-truncate mb-0"><?= $departmentlogin ?></h6>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-4">
                                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                        <i class="ri-user-2-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="mb-1">Section </p>
                                                    <h6 class="text-truncate mb-0"><?= $sectionlogin ?></h6>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-4">
                                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                        <i class="ri-user-2-fill"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="mb-1">Position </p>
                                                    <h6 class="text-truncate mb-0"><?= $positionlogin ?></h6>
                                                </div>
                                            </div>


                                        </div>
                                        <!--end col-->
                                        <div class="col-6 col-md-4">
                                            <div class="d-flex mt-4">
                                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                        <i class="ri-global-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="mb-1">Klasifikasi </p>
                                                    <h6 class="text-truncate mb-0"><?= $clasifikasilogin ?></h6>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-4">
                                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                        <i class="ri-global-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="mb-1">Atasan </p>
                                                    <h6 class="text-truncate mb-0">
                                                        <?= $atasanlogin ?>
                                                    </h6>
                                                </div>
                                            </div>

                                            <div class="d-flex mt-4">
                                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                        <i class="ri-global-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="mb-1">POH </p>
                                                    <h6 class="text-truncate mb-0"><?= $pohlogin ?></h6>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-4">
                                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                        <i class="ri-global-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="mb-1">Roster </p>
                                                    <h6 class="text-truncate mb-0"><?= $rosterlogin ?></h6>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-4">
                                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                        <i class="ri-global-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="mb-1">DOH </p>
                                                    <h6 class="text-truncate mb-0"><?= $dohlogin ?></h6>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-4">
                                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary">
                                                        <i class="ri-global-line"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="mb-1">Status </p>
                                                    <h6 class="text-truncate mb-0"><?= $statuslogin ?></h6>
                                                </div>
                                            </div>




                                        </div>
                                        <!--end col-->
                                    </div>

                                </div>
                                <!--end card-body-->
                            </div><!-- end card -->

                            <!-- end row -->

                            <!-- end card -->

                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>

                <!--end tab-pane-->
                <div class="tab-pane fade" id="projects" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-sm-4">
                                    <div class="search-box">
                                        <input type="text" class="form-control" id="searchMemberList" placeholder="Search for name ">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-sm-auto ms-auto">

                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                    </div>

                    <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="">
                        <!--end offcanvas-header-->
                        <div class="offcanvas-body profile-offcanvas p-0">
                            <div class="team-cover">
                                <img src="assets/images/small/img-9.jpg" alt="" class="img-fluid" />
                            </div>
                            <div class="p-3">
                                <div class="team-settings">
                                    <div class="row">
                                        <div class="col">
                                            <div class="bookmark-icon flex-shrink-0 me-2">
                                                <input type="checkbox" id="favourite13" class="bookmark-input bookmark-hide">
                                                <label for="favourite13" class="btn-star">
                                                    <svg width="20" height="20">
                                                        <use xlink:href="#icon-star" />
                                                    </svg>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col text-end dropdown">
                                            <a href="javascript:void(0);" id="dropdownMenuLink14" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill fs-17"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink14">
                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-eye-line me-2 align-middle"></i>View</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-star-line me-2 align-middle"></i>Favorites</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);"><i class="ri-delete-bin-5-line me-2 align-middle"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <div class="p-3 text-center">
                                <img src="assets/images/users/avatar-2.jpg" alt="" class="avatar-lg img-thumbnail rounded-circle mx-auto profile-img">
                                <div class="mt-3">
                                    <h5 class="fs-15 profile-name">Nancy Martino</h5>
                                    <p class="text-muted profile-designation">Team Leader & HR</p>
                                </div>
                                <div class="hstack gap-2 justify-content-center mt-4">
                                    <div class="avatar-xs">
                                        <a href="javascript:void(0);" class="avatar-title bg-soft-secondary text-secondary rounded fs-16">
                                            <i class="ri-facebook-fill"></i>
                                        </a>
                                    </div>
                                    <div class="avatar-xs">
                                        <a href="javascript:void(0);" class="avatar-title bg-soft-success text-success rounded fs-16">
                                            <i class="ri-slack-fill"></i>
                                        </a>
                                    </div>
                                    <div class="avatar-xs">
                                        <a href="javascript:void(0);" class="avatar-title bg-soft-info text-info rounded fs-16">
                                            <i class="ri-linkedin-fill"></i>
                                        </a>
                                    </div>
                                    <div class="avatar-xs">
                                        <a href="javascript:void(0);" class="avatar-title bg-soft-danger text-danger rounded fs-16">
                                            <i class="ri-dribbble-fill"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-0 text-center">
                                <div class="col-6">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1 profile-project">124</h5>
                                        <p class="text-muted mb-0">Projects</p>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1 profile-task">81</h5>
                                        <p class="text-muted mb-0">Tasks</p>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                            <div class="p-3">
                                <h5 class="fs-15 mb-3">Personal Details</h5>
                                <div class="mb-3">
                                    <p class="text-muted text-uppercase fw-semibold fs-12 mb-2">Number</p>
                                    <h6>+(256) 2451 8974</h6>
                                </div>
                                <div class="mb-3">
                                    <p class="text-muted text-uppercase fw-semibold fs-12 mb-2">Email</p>
                                    <h6>nancymartino@email.com</h6>
                                </div>
                                <div>
                                    <p class="text-muted text-uppercase fw-semibold fs-12 mb-2">Location</p>
                                    <h6 class="mb-0">Carson City - USA</h6>
                                </div>
                            </div>
                            <div class="p-3 border-top">
                                <h5 class="fs-15 mb-4">File Manager</h5>
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0 avatar-xs">
                                        <div class="avatar-title bg-soft-danger text-danger rounded fs-16">
                                            <i class="ri-image-2-line"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><a href="javascript:void(0);">Images</a></h6>
                                        <p class="text-muted mb-0">4469 Files</p>
                                    </div>
                                    <div class="text-muted">
                                        12 GB
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0 avatar-xs">
                                        <div class="avatar-title bg-soft-secondary text-secondary rounded fs-16">
                                            <i class="ri-file-zip-line"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><a href="javascript:void(0);">Documents</a></h6>
                                        <p class="text-muted mb-0">46 Files</p>
                                    </div>
                                    <div class="text-muted">
                                        3.46 GB
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0 avatar-xs">
                                        <div class="avatar-title bg-soft-success text-success rounded fs-16">
                                            <i class="ri-live-line"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><a href="javascript:void(0);">Media</a></h6>
                                        <p class="text-muted mb-0">124 Files</p>
                                    </div>
                                    <div class="text-muted">
                                        4.3 GB
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="flex-shrink-0 avatar-xs">
                                        <div class="avatar-title bg-soft-primary text-primary rounded fs-16">
                                            <i class="ri-error-warning-line"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><a href="javascript:void(0);">Others</a></h6>
                                        <p class="text-muted mb-0">18 Files</p>
                                    </div>
                                    <div class="text-muted">
                                        846 MB
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end offcanvas-body-->
                        <div class="offcanvas-foorter border p-3 hstack gap-3 text-center position-relative">
                            <button class="btn btn-light w-100"><i class="ri-question-answer-fill align-bottom ms-1"></i> Send Message</button>
                            <a href="pages-profile.html" class="btn btn-primary w-100"><i class="ri-user-3-fill align-bottom ms-1"></i> View Profile</a>
                        </div>
                    </div>
                    <!--end card-->
                </div>
                <!--end tab-pane-->
                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-borderless align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col">File Name</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Size</th>
                                                    <th scope="col">Upload Date</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php $sql = mysqli_query($koneksi, "SELECT * FROM dokumen_user WHERE idnik ='$niklogin' ");
                                                $nomor = 1;
                                                while ($row = mysqli_fetch_assoc($sql)) {

                                                ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm">
                                                                    <div class="avatar-title bg-soft-primary text-primary rounded fs-20">
                                                                        <i class="ri-file-zip-fill"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-3 flex-grow-1">
                                                                    <h6 class="fs-15 mb-0"><a href="file/dokumen-user/<?= $row['file_dok'] ?>"><?= $row['nama_dok'] ?></a>
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><?php $walii = "file/dokumen-user/" . $row['file_dok'] . "";
                                                            echo fsize($walii) ?></td>
                                                        <td><?php
                                                            $x = explode('.', $walii);
                                                            $ekstensi = strtolower(end($x));
                                                            echo "$ekstensi" ?></td>
                                                        <td><?= date(('d F Y'), strtotime($row['tgl_dok'])) ?></td>


                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end tab-pane-->
            </div>
            <!--end tab-content-->
        </div>
    </div>
    <!--end col-->
</div>
<!--end row-->



<script src="assets/js/pages/profile-setting.init.js"></script>
 <script src="assets/js/pages/passowrd-create.init.js"></script>
<div class="h-100">
    <div class="row mb-3 pb-1">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-16 mb-1">Welcome, <?= $namalogin ?></h4>
                    <p class="text-muted mb-0">Silahkan Klik Bagian Menu Kiri, Untuk mempelajari seluruh informasi
                        mengenai Perusahaan. </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Aset IT</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"> <?php
                        $sql = mysqli_query($koneksi, "SELECT COUNT(*) AS total_aset FROM aset_it");
                        $data = mysqli_fetch_assoc($sql);
                        $total_aset = $data['total_aset'];
                        echo $total_aset;
                        ?> </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i id="device-icon" class="ri-database-2-fill" style="color:#FFD700; font-size: 50px;"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Aset Area</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <?php
                            $sql = mysqli_query($koneksi, "
                                SELECT COUNT(*) AS total_out_user
                                    FROM (
                                        SELECT id_aset_it, status
                                        FROM histori_aset_it
                                        WHERE tanggal = (
                                            SELECT MAX(tanggal)
                                            FROM histori_aset_it AS sub
                                            WHERE sub.id_aset_it = histori_aset_it.id_aset_it
                                        )
                                    ) AS latest_status
                                    WHERE status = 'Out-Area'
                            ");
                            $result = mysqli_fetch_assoc($sql);
                            $TotalOutUser = $result['total_out_user'];
                            echo $TotalOutUser;
                            ?>
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i class="bx bx-user-circle text-warning"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Aset User</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <?php
                            $sql = mysqli_query($koneksi, "
                                SELECT COUNT(*) AS total_out_user
                                    FROM (
                                        SELECT id_aset_it, status
                                        FROM histori_aset_it
                                        WHERE tanggal = (
                                            SELECT MAX(tanggal)
                                            FROM histori_aset_it AS sub
                                            WHERE sub.id_aset_it = histori_aset_it.id_aset_it
                                        )
                                    ) AS latest_status
                                    WHERE status = 'Out-User'
                            ");
                            $result = mysqli_fetch_assoc($sql);
                            $TotalOutUser = $result['total_out_user'];
                            echo $TotalOutUser;
                            ?>
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i class="bx bx-user-circle text-warning"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Gudang</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <?php
                            $sql = mysqli_query($koneksi, "
                                SELECT COUNT(*) AS total_out_user
                                    FROM (
                                        SELECT id_aset_it, status
                                        FROM histori_aset_it
                                        WHERE tanggal = (
                                            SELECT MAX(tanggal)
                                            FROM histori_aset_it AS sub
                                            WHERE sub.id_aset_it = histori_aset_it.id_aset_it
                                        )
                                    ) AS latest_status
                                    WHERE status = 'IN-Gudang'
                            ");
                            $result = mysqli_fetch_assoc($sql);
                            $TotalOutUser = $result['total_out_user'];
                            echo $TotalOutUser;
                            ?>
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i class="bx bx-user-circle text-warning"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Maintenance</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <?php
                            $sql = mysqli_query($koneksi, "
                                SELECT COUNT(*) AS total_out_user
                                    FROM (
                                        SELECT id_aset_it, status
                                        FROM histori_aset_it
                                        WHERE tanggal = (
                                            SELECT MAX(tanggal)
                                            FROM histori_aset_it AS sub
                                            WHERE sub.id_aset_it = histori_aset_it.id_aset_it
                                        )
                                    ) AS latest_status
                                    WHERE status = 'Maintenance'
                            ");
                            $result = mysqli_fetch_assoc($sql);
                            $TotalOutUser = $result['total_out_user'];
                            echo $TotalOutUser;
                            ?>
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i class="bx bx-user-circle text-warning"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Belum Terdata</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                            <?php
                            $sql = mysqli_query($koneksi, "
    SELECT
        COUNT(*) AS total_not_recorded
    FROM
        aset_it ai
    LEFT JOIN
        histori_aset_it ha ON ai.id_aset_it = ha.id_aset_it
    WHERE
        ha.id_aset_it IS NULL
");
                            $result = mysqli_fetch_assoc($sql);
                            $TotalNotRecorded = $result['total_not_recorded'];
                            echo $TotalNotRecorded;
                            ?>
                        </h4>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i class="bx bx-user-circle text-warning"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/libs/swiper/swiper-bundle.min.js"></script>
<script src="assets/js/pages/nft-landing.init.js"></script>
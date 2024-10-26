<?php
include_once("koneksi.php");

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $isi = $_POST['isi'];
    $tgl_awal = $_POST['tgl_awal'];
    $tgl_akhir = $_POST['tgl_akhir'];
    
    if ($id) {
        $query = "UPDATE kegiatan SET isi='$isi', tgl_awal='$tgl_awal', tgl_akhir='$tgl_akhir' WHERE id='$id'";
        mysqli_query($mysqli, $query);
    } else {
        $query = "INSERT INTO kegiatan (isi, tgl_awal, tgl_akhir, status) VALUES ('$isi', '$tgl_awal', '$tgl_akhir', '0')";
        mysqli_query($mysqli, $query);
    }
    header("Location: index.php");
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($mysqli, "DELETE FROM kegiatan WHERE id='$id'");
    header("Location: index.php");
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'ubah_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    mysqli_query($mysqli, "UPDATE kegiatan SET status='$status' WHERE id='$id'");
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>To Do List</title>
</head>
<body>
<div class="container">
    <br>
    <h3>To Do List <small class="text-muted"> Catat semua hal yang akan kamu kerjakan disini.</small></h3>
    <hr>
    <form class="form row" method="POST" action="">
        <?php
        $isi = '';
        $tgl_awal = '';
        $tgl_akhir = '';
        $id = '';

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $ambil = mysqli_query($mysqli, "SELECT * FROM kegiatan WHERE id='$id'");
            $row = mysqli_fetch_array($ambil);
            $isi = $row['isi'];
            $tgl_awal = $row['tgl_awal'];
            $tgl_akhir = $row['tgl_akhir'];
        }
        ?>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div class="col mb-2">
            <label for="inputIsi" class="form-label fw-bold">Kegiatan</label>
            <input type="text" class="form-control" name="isi" id="inputIsi" placeholder="Kegiatan" value="<?php echo $isi; ?>" required>
        </div>
        
        <div class="col mb-2">
            <label for="inputTanggalAwal" class="form-label fw-bold">Tanggal Awal</label>
            <input type="date" class="form-control" name="tgl_awal" id="inputTanggalAwal" value="<?php echo $tgl_awal; ?>" required>
        </div>
        
        <div class="col mb-2">
            <label for="inputTanggalAkhir" class="form-label fw-bold">Tanggal Akhir</label>
            <input type="date" class="form-control" name="tgl_akhir" id="inputTanggalAkhir" value="<?php echo $tgl_akhir; ?>" required>
        </div>
        
        <div class="col">
            <button type="submit" class="btn btn-primary rounded-pill px-3" name="simpan">Simpan</button>
        </div>
    </form>
    <table class="table table-hover mt-3">
        <thead>
        <tr>
            <th>#</th>
            <th>Kegiatan</th>
            <th>Awal</th>
            <th>Akhir</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $result = mysqli_query($mysqli, "SELECT * FROM kegiatan ORDER BY status, tgl_awal");
        $no = 1;
        while ($data = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <th><?php echo $no++; ?></th>
                <td><?php echo $data['isi']; ?></td>
                <td><?php echo $data['tgl_awal']; ?></td>
                <td><?php echo $data['tgl_akhir']; ?></td>
                <td>
                    <?php if ($data['status'] == '1') { ?>
                        <a class="btn btn-success rounded-pill px-3" href="index.php?id=<?php echo $data['id']; ?>&aksi=ubah_status&status=0">Sudah</a>
                    <?php } else { ?>
                        <a class="btn btn-warning rounded-pill px-3" href="index.php?id=<?php echo $data['id']; ?>&aksi=ubah_status&status=1">Belum</a>
                    <?php } ?>
                </td>
                <td>
                    <a class="btn btn-info rounded-pill px-3" href="index.php?id=<?php echo $data['id']; ?>">Ubah</a>
                    <a class="btn btn-danger rounded-pill px-3" href="index.php?id=<?php echo $data['id']; ?>&aksi=hapus">Hapus</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>

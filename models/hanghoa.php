
<?php
function hanghoa_ct($ma_hh)
{
  
    $conn = connection();
    $sql = "SELECT * FROM hang_hoa where ma_hh = $ma_hh";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}
function hanghoa_tang_so_luot_xem($ma_hh)
{
  
    $conn = connection();
    $sql = "UPDATE hang_hoa SET so_luot_xem = so_luot_xem + 1 WHERE hang_hoa.ma_hh = $ma_hh";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}
function hanghoa_cl($ma_hh){
    $data[] = $ma_hh;
    $conn = connection();
    $sql="select * from hang_hoa where  ma_hh =".$ma_hh;
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
    $hanghoa = $stmt->fetchAll();
    return $hanghoa;
}

function hanghoa_by_ma_hanghoa($ma_hh)
{
    $conn = connection();
    $sql = "SELECT * FROM hang_hoa 
    INNER JOIN hinh_hang_hoa ON hinh_hang_hoa.ma_hh = hang_hoa.ma_hh 
    INNER JOIN chi_tiet_hang_hoa ON chi_tiet_hang_hoa.ma_hh = hang_hoa.ma_hh 
    WHERE hang_hoa.ma_hh = $ma_hh";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $hangHoaChiTiet = [

        'ma_hh' => $result[0]['ma_hh'],
       
        'ten_hh' => $result[0]['ten_hh'],
        'mo_ta' => $result[0]['mo_ta'],
        'dac_biet' => $result[0]['dac_biet'],
        'so_luot_xem' => $result[0]['so_luot_xem'],
        'ma_loai' => $result[0]['ma_loai'],
        'hinhArr' => [],
        'chi_tiet_sp' => [],
    ];
    foreach ($result as $row) {
        $maHinh = $row['ma_hinh'];
        $maCthh = $row['ma_cthh'];
        if(!isset($hangHoaChiTiet['hinhArr'][$maHinh])) {
            $hangHoaChiTiet['hinhArr'][$maHinh] = $row['ten_hinh'];
        }
        if(!isset($hangHoaChiTiet['chi_tiet_sp'][$maCthh])) {
            $hangHoaChiTiet['chi_tiet_sp'][$maCthh] = [
                'don_vi' => $row['don_vi'],
                'don_gia' => $row['don_gia'],
                'giam_gia' => $row['giam_gia'],
                'so_luong' => $row['so_luong'],
            ];
        }
    }
   
    return $hangHoaChiTiet;
}

function hanghoa_by_ma_loai($ma_loai)
{
    $conn = connection();
    $sql = "SELECT * FROM hang_hoa 
    INNER JOIN hinh_hang_hoa ON hinh_hang_hoa.ma_hh = hang_hoa.ma_hh 
    INNER JOIN chi_tiet_hang_hoa ON chi_tiet_hang_hoa.ma_hh = hang_hoa.ma_hh 
    WHERE hang_hoa.ma_loai = $ma_loai
    ORDER BY hang_hoa.ma_hh ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $hangHoaArr = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $maHH = $row['ma_hh'];
        $maHinh = $row['ma_hinh'];
        $maCthh = $row['ma_cthh'];
        if(!isset($hangHoaArr[$maHH])) {
            $hangHoaArr[$maHH] = [
                'ma_hh' => $row['ma_hh'],
                'ten_hh' => $row['ten_hh'],
                'hinhArr' => [],
                'chi_tiet_sp' => []
            ];
        }
        if(!isset($hangHoaArr[$maHH]['hinhArr'][$maHinh])) {
            $hangHoaArr[$maHH]['hinhArr'][$maHinh] = $row['ten_hinh'];
        }
        if(!isset($hangHoaArr[$maHH]['chi_tiet_sp'][$maCthh])) {
            $hangHoaArr[$maHH]['chi_tiet_sp'][$maCthh] = [
                'ma_cthh' => $row['ma_cthh'],
                'don_vi' => $row['don_vi'],
                'don_gia' => $row['don_gia'],
                'giam_gia' => $row['giam_gia'],
                'so_luong' => $row['so_luong'],
            ];
        }
    }
    // echo "<pre>";
    // print_r($hangHoaArr);
    return $hangHoaArr;
}
?>
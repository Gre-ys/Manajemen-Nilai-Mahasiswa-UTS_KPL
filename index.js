// Jquery Untuk Manipulasi Modal
$(() => {
  // Sesuaikan Modal Form untuk Keperluan Inset Data
  $("#trigger_insert").click(function () {
    $("#modal_label").html("Insert Data");
    $("#modal_button").attr("name", "submit_insertData");
    $("#modal_button").html("Insert");
    $("#id").val("");
    $("#dosen_id").val("");
    $("#npm").val("");
    $("#nama").val("");
    $("#nama_mk").val("");
    $("#nKehadiran").val("");
    $("#nTugas").val("");
    $("#nQuiz").val("");
    $("#nUts").val("");
    $("#nUas").val("");
  });

  // Sesuaikan Modal Form untuk Keperluan Update Data
  $(".trigger_update").click(function () {
    let id = $(this).data("id");
    $("#modal_label").html("Update Data");
    $("#modal_button").attr("name", "submit_updateData");
    $("#modal_button").html("Update");

    // AJAX untuk Get Data Mahasiswa Sesuai Id Tertentu
    $.ajax({
      url: "ajax.php",
      data: { id: id },
      method: "POST",
      dataType: "JSON",
      success: function (data) {
        // Inisialisasi Form dalam Modal untuk di Update
        $("#id").val(data.id);
        $("#dosen_id").val(data.dosen_id);
        $("#npm").val(data.npm);
        $("#nama").val(data.nama);
        $("#nama_mk").val(data.nama_mk);
        $("#nKehadiran").val(data.nKehadiran);
        $("#nTugas").val(data.nTugas);
        $("#nQuiz").val(data.nQuiz);
        $("#nUts").val(data.nUts);
        $("#nUas").val(data.nUas);
      },
    });
  });
});

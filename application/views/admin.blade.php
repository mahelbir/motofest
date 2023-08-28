<!DOCTYPE html>
<html lang="{{language()}}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="@asset('css/bootstrap.min.css')">
    <title>Admin | @sets("site.name")</title>
</head>
<body style="background-color: #e6e6e6;">

<div class="container-fluid">
    <div class="mt-5">
        <div class="row">

            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-header"><h4>Yeni Ürün</h4></div>
                    <div class="card-body">
                        <form autocomplete="off">
                            <div class="form-group">
                                <input type="text" class="form-control" name="isim" placeholder="İsim" required>
                            </div>
                            <div class="form-group">
                                <input type="number" step="0.01" class="form-control" name="fiyat" placeholder="Fiyat"
                                       required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="resim" placeholder="Resim Linki">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Ekle</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>Gün</th>
                                <th>Ciro</th>
                                <th>Sipariş</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($siparisler as $siparis)
                                <tr>
                                    <td>{{$siparis['gun']}}</td>
                                    <td>{{$siparis['ciro']}}</td>
                                    <td>{{$siparis['adet']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="@asset('js/jquery.min.js')"></script>
<script src="@asset('js/print.min.js')"></script>

<script>
    $('form').submit(function (event) {
        event.preventDefault();
        const formData = $(this).serializeArray();
        $.post({
            url: "@base_url('admin/urun')",
            dataType: "json",
            data: formData
        })
            .done(res => {
                if (res?.id)
                    alert("Ürün eklendi.")
                else
                    alert(res?.error ?? "Bir hata oluştu!");
            })
            .fail(xhr => {
                alert(xhr?.responseJSON?.error ?? 'Sunucu hatası!');
            })
            .always(() => location.reload());
    });
</script>

</body>
</html>
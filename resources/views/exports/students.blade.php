<style>

    /* Set font family Times New Roman */

    @font-face {

        font-family: 'Times New Roman';

        /* src: url({{ storage_path('fonts/times.ttf') }}); */

    }
    .main{
        width: 100%;
        height: 100%;
    }
    .container{
        width: 100%;
        height: 100%;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }
    .title{
        text-align: center;
        text-transform: uppercase;
    }
    .student-name{
        text-align: center;
        text-transform: uppercase;
        margin: 15px 0px;
    }
    table{
        margin: 10px auto;
        width: 100%
    }

    .table-scores th, .table-scores td {
        border: .5px solid #cacaca;
        font-size: 13px;
        text-align: center;
    }

    .table-average th, .table-average td {
        border: 1px solid black;
        font-size: 13px;
    }

    .table-leyend th, .table-leyend td {
        border: 1px solid black;
        font-size: 13px;
    }
    .table-leyend{
        max-width: 300px;
        margin-left: auto;
    }

    .more-spacing{
        padding: 0 10px;
    }
    .black-blue{
        background: #a0c4e4;
    }
    .header-gray{
        background: #d4dce4;
    }

    .leyend{
        font-weight: 900;
        margin-bottom: -8px;
    }
</style>

<div class='main'>
    <div class='container'>
        <div class='header'>
            <img width="100%" src="https://projects.andresjosehr.com/noraruoti//ceritificate-header.png" alt="">
        </div>
        {{-- <div class='text'>
            Estudiantes egresados
        </div> --}}
        <h1 class='student-name'>Estudiantes {{$type}}</h1>

        <table cellspacing="0" class='table table-scores'>
            <thead>
                <tr>
                    <th class='black-blue'>Nombres y Apellidos</th>
                    <th class='black-blue'>Cedula</th>
                    <th class='black-blue' style='width:10px'>Email</th>
                    <th class='black-blue'>Telefono</th>
                    <th class='black-blue'>Carrera</th>
                </tr>
            </thead>
            <tbody>
                {{-- foreach scores --}}
                    @foreach ($students as $student)
                        <tr>
                            <td style='text-transform: uppercase; padding:5px'>{{$student->Nombre}} {{$student->Apellido}}</td>
                            <td>{{$student->Cedula}}</td>
                            <td>{{$student->Email}}</td>
                            <td>{{$student->Telefono}}</td>
                            <td>{{$student->Carrera}}</td>
                        </tr>
                    @endforeach
            </tbody>
        </table>

    </div>
</div>

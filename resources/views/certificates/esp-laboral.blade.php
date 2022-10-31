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
        <div class='title'>
            <h1>Certificado de estudios</h1>
        </div>
        <div class='text'>
            La Directora General, Prof. Mag. Nora Lucía Ruoti Cosp, y la Secretaria General, Esc. Raquel León Garay, del Instituto Superior de Formación Tributaria, Comercial y Administrativa (FOTRIEM), certifican que:
        </div>
        <h1 class='student-name'>{{$student->first_name}} {{$student->last_name}}</h1>
        <div class='text'>
            Con C.I. Nº <span class='document-number'>{{$student->document}}</span>, de nacionalidad paraguaya, obtuvo las calificaciones finales obrantes en los libros respectivos de la Institución que corresponden al programa de postgrado de ESPECIALIZACIÓN EN LEGISLACIÓN Y PRÁCTICA LABORAL, aprobado por Res. CONES Nº 407/19. Sede Asunción. Modalidad Presencial y que a continuación se detallan:
        </div>

        <table cellspacing="0" class='table table-scores'>
            <thead>
                <tr>
                    <th rowspan="2" class='black-blue' style="width: 90%;">Asignatura / Módulo</th>
                    <th rowspan="2" class='black-blue'>Código</th>
                    <th colspan="2" class='black-blue more-spacing'>Calificaciones</th>
                    <th rowspan="2" class='black-blue'>Carga Horaria</th>
                    <th rowspan="2" class='black-blue'>Créditos</th>
                    <th rowspan="2" class='black-blue more-spacing'>Nº Acta</th>
                    <th rowspan="2" class='black-blue more-spacing'>Fecha de Evaluación</th>
                    <th rowspan="2" class='black-blue'>Período de Evaluación</th>
                </tr>
                <tr>
                    <th class='header-gray'>Nota Final</th>
                    <th class='header-gray'>Letras</th>
                </tr>
            </thead>
            <tbody>
                {{-- foreach scores --}}
                    @foreach ($scores as $score)
                        <tr>
                            <td style='text-transform: uppercase'>{{$score->asignature->name}}</td>
                            <td>{{$score->asignature->code}}</td>
                            <td>{{$score->total_score}}</td>
                            <td>{{$score->letters}}</td>
                            <td>{{$score->asignature->workload}}</td>
                            <td>{{$score->asignature->credit}}</td>
                            <td>{{$score->final_evaluation_report}}</td>
                            <td>{{$score->exam_date}}</td>
                            <td>{{$score->period}}</td>
                        </tr>
                    @endforeach
            </tbody>
        </table>

        <div class='tables-footer'>
            <table>
                <tbody>
                    <tr>
                        <td>
                            <div class='table-average'>
                                <table class='table'>
                                    <tbody>
                                        <tr>
                                            <td style="font-weight: 900">Promedio General</td>
                                            <td>{{
                                                number_format($scores->avg('total_score'), 2)
                                            }}</td>
                                        </tr>
                                        <tr>
                                            <td>Carga horaria total</td>
                                            <td>{{
                                                $scores->sum(function ($score) {
                                                    return $score->asignature->workload;
                                                })
                                            }}</td>
                                        </tr>
                                        <tr>
                                            <td>Total creditos obtenidos</td>
                                            <td>{{
                                                $scores->sum(function ($score) {
                                                    return $score->asignature->credits;
                                                })
                                            }}</td>
                                        </tr>
                                        <tr>
                                            <td>Año de culminación</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                        <td>
                            <div class='table-leyend'>
                                <div class='leyend'>Escala de Calificaciones del 2 al 5:</div>
                                <table class='table'>
                                    <tbody>
                                        <tr>
                                            <td>2 (Dos) = Reprobado</td>
                                            <td>4 (Cuatro) = Muy Bueno</td>
                                        </tr>
                                        <tr>
                                            <td>3 (Tres) = Bueno</td>
                                            <td>5 (Cinco) = Sobresaliente		</td>
                                        </tr>
                                        <tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

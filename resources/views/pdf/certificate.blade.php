<html lang="en">
    <head>
        <style>
            h3{ text-align:center; font-weight:200px }
            h2{ text-align:center; font-size:30px }
            p{ margin: 0 70px; line-height:2; font-size:20px }
            span{ text-align:center; font-weight:700; font-size:21px; margin:0 10px }
            .date{ text-align:right }
            .signature{ display:inline; float:left; text-align:center; margin-top:20px; line-height:1.5}
        </style>
    </head>
    <body>
        <h3 >République Algerienne Démocratique et Populaire</h3>
        <h2>ATTESTATION DE STAGE</h2>
        <p>Je, soussigné(e) <span>{{$supervisorLastName}} {{$supervisorFirstName}}</span> responsable de stage de <span>{{$title}}</span><br>
            atteste que l'étudiant(e) <span>{{$lastName}} {{$firstName}}</span> né(e) le <span>{{$birthDate}}</span> à <span>{{$birthPlace}}</span>
            inscrit(e) à L’Université Abdel Hamid Mehri Constantine 2,<br>
            a effectué un stage de formation dans la filière <span>{{$department}}</span>specialité<span>{{$speciality}}</span>
            à <span>{{$company}}</span> , <span>{{$address}}</span><br>
            Durant la période du <span>{{$startDate}}</span> au <span>{{$endDate}}</span>.</p>
        <p class='date'>fait à Constantine le {{$date}}</p>
            <p class='signature' style='margin-left:150px'>Le Représentant de L’Université <br>Abdel Hamid Mehri Constantine 2 </p>
            <p class='signature'>Le Responsable de l'établissement<br> {{$company}}</p>
    </body>
</html>

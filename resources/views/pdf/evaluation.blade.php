<html lang="en">
<h2>Etablissement d'accueil : {{$company}}</h2>
<h1>FICHE DE NOTATION DE STAGE</h1>
<p>
    Nom et prenom de stagiaire : {{$lastName}} {{$firstName}} <br>
    né(e) le {{$birthDate}} à {{$birthPlace}}<br>
    Inscrit(e) en : {{$speciality}}, Semestre : {{$semester}} <br>
    Diplome préparé : {{$speciality}} <br>
    Durée du stage : {{$duration}} - Periode du : {{$startDate}} au {{$endDate}} <br>
    Etablissement d'accueil : {{$company}}, Lieu : {{$address}} <br>
    Plan de travail réalisé par le stagiaire : {{$title}}<br>
</p>
<table style="border: 1px solid #1a202c">
    <tr>
        <th style="text-align: left; padding: 10px 5px; border-right: 1px solid #1a202c; border-bottom: 1px solid #1a202c">Aptitudes</th>
        <th style="text-align: left; padding: 10px 5px; border-bottom: 1px solid #1a202c">Evaluation</th>
    </tr>
    <tr>
        <td style="text-align: left; padding: 10px 5px; border-right: 1px solid #1a202c; border-bottom: 1px solid #1a202c">Discipline générale et relations humaines</td>
        <td style="text-align: left; padding: 10px 5px; border-bottom: 1px solid #1a202c">{{$discipline}} / 4</td>
    </tr>
    <tr>
        <td style="text-align: left; padding: 10px 5px; border-right: 1px solid #1a202c; border-bottom: 1px solid #1a202c">Aptitudes au travail et à la manipulation</td>
        <td style="text-align: left; padding: 10px 5px; border-bottom: 1px solid #1a202c">{{$aptitude}} / 4</td>
    </tr>
    <tr>
        <td style="text-align: left; padding: 10px 5px; border-right: 1px solid #1a202c; border-bottom: 1px solid #1a202c">Initiative/entreprenariat</td>
        <td style="text-align: left; padding: 10px 5px; border-bottom: 1px solid #1a202c">{{$initiative}} / 4</td>
    </tr>
    <tr>
        <td style="text-align: left; padding: 10px 5px; border-right: 1px solid #1a202c; border-bottom: 1px solid #1a202c">Capacités d'imagination et d'innovation</td>
        <td style="text-align: left; padding: 10px 5px; border-bottom: 1px solid #1a202c">{{$innovation}} / 4</td>
    </tr>
    <tr>
        <td style="text-align: left; padding: 10px 5px; border-right: 1px solid #1a202c; border-bottom: 1px solid #1a202c">Connaissances acquises sur le terrain de stage</td>
        <td style="text-align: left; padding: 10px 5px; border-bottom: 1px solid #1a202c">{{$acquiredKnowledge}} / 4</td>
    </tr>
    <tr>
        <th style="text-align: left; padding: 10px 5px; border-right: 1px solid #1a202c">Note totale</th>
        <th style="text-align: left; padding: 10px 5px">{{$total}} / 20</th>
    </tr>
</table>
<p>Appreciation globale : {{$globalAppreciation}}</p>
<p>Date : {{$date}}</p>
<p>Sceau de l'Etablissement d'accueil</p>
</html>

<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;

class RTTCalculatorController extends Controller
{
    public function calculateRTTForYear(Request $request)
    {
        $year = $request->input('year'); // Récupération de l'année depuis la requête
        $cp = $request->input('paid_leave_days'); // Récupération des CP
        $effectiveWorkDays = $request->input('worked_days'); // Récupération des jours travaillés

        // Vérification si l'année est bissextile
        $isLeapYear = date('L', mktime(0, 0, 0, 1, 1, $year));

        // Nombre total de jours dans l'année
        $totalDays = $isLeapYear ? 366 : 365;

        // Calcul des samedis et dimanches
        $weekends = $this->calculateWeekends($year);

        // Calcul des jours fériés ne tombant pas le weekend
        $publicHolidays = $this->calculatePublicHolidays($year);

        // Calcul des RTT
        $rttDays = $totalDays - $weekends - $publicHolidays - $effectiveWorkDays - $cp;

        return redirect('/')
            ->withInput() // Garde les entrées du formulaire
            ->with([
                'rtt_days' => $rttDays, // Le résultat du calcul des RTT
                'year' => $year,
                'isLeapYear' => $isLeapYear,
                'weekends' => $weekends,
                'publicHolidays' => $publicHolidays,
            ]);
    }

    private function calculateWeekends($year)
    {
        $weekendDays = 0;

        // Compter les samedis et dimanches pour chaque mois
        for ($month = 1; $month <= 12; $month++) {
            // Obtenir le nombre de jours dans le mois
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            for ($day = 1; $day <= $daysInMonth; $day++) {
                // Obtenir le jour de la semaine (0 pour dimanche, 6 pour samedi)
                $weekDay = date('w', mktime(0, 0, 0, $month, $day, $year));

                // Vérifier si le jour est un samedi ou un dimanche
                if ($weekDay == 0 || $weekDay == 6) {
                    $weekendDays++;
                }
            }
        }

        return $weekendDays;
    }

    private function calculatePublicHolidays($year)
    {
        // Jours fériés fixes (Mois-Jour)
        $fixedHolidays = [
            '1-1',   // Nouvel An
            '5-1',   // Fête du Travail
            '5-8',   // Victoire 1945
            '7-14',  // Fête Nationale
            '8-15',  // Assomption
            '11-1',  // Toussaint
            '11-11', // Armistice
            '12-25', // Noël
        ];

        // Calcul des jours fériés mobiles
        // Lundi de Pâques: le lendemain du dimanche de Pâques
        $easterDay = date('j', easter_date($year));
        dump(date('Y-m-d', easter_date($year)));
        $easterMonth = date('n', easter_date($year));
        $easterMonday = date('Y-m-d', strtotime("$year-$easterMonth-$easterDay +2 days"));
        dump($easterMonday);

        // Ascension: 40 ième jour après Pâques
        $ascensionDay = date('Y-m-d', strtotime("$year-$easterMonth-$easterDay +41 days"));
        dump($ascensionDay);

        // Lundi de Pentecôte: 50 ième jour après Pâques
        $pentecostMonday = date('Y-m-d', strtotime("$year-$easterMonth-$easterDay +51 days"));
        dump($pentecostMonday);

        // Ajout des jours fériés mobiles à la liste
        $mobileHolidays = [
            $easterMonday,
            $ascensionDay,
            $pentecostMonday,
        ];

        $publicHolidaysNotOnWeekend = 0;

        // Vérification des jours fériés fixes
        foreach ($fixedHolidays as $fixedHoliday) {
            $date = DateTime::createFromFormat('n-j-Y', "$fixedHoliday-$year");
            if ($date->format('N') < 6) { // Jours de la semaine (1 = lundi, 5 = vendredi)
                $publicHolidaysNotOnWeekend++;
            }
        }

        // Vérification des jours fériés mobiles
        foreach ($mobileHolidays as $mobileHoliday) {
            $date = new DateTime($mobileHoliday);
            if ($date->format('N') < 6) { // Jours de la semaine
                $publicHolidaysNotOnWeekend++;
            }
        }

        return $publicHolidaysNotOnWeekend;
    }
}

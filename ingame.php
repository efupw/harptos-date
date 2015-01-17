<?php
  class ingame {

    public function getDate() {
      return $this->getEFUDate();
    }

    private function getMonthLength($month, $year) {
      $fouryear = 0;
      if (($year % 4) == 0) $fouryear = 1;
      if ($month == 1) return 31;
      if ($month == 4) return 31;
      if ($month == 7) return $fouryear + 31;
      if ($month == 9) return 31;
      if ($month == 11) return 31;
      return 30;
    }

    private function getMonthName($month) {
      switch ($month) {
        case 1: return "Hammer";
        case 2: return "Alturiak";
        case 3: return "Ches";
        case 4: return "Tarsakh";
        case 5: return "Mirtul";
        case 6: return "Kythorn";
        case 7: return "Flamerule";
        case 8: return "Eleasis";
        case 9: return "Eleint";
        case 10: return "Marpenoth";
        case 11: return "Uktar";
        case 12: return "Nightal";
        default: return "*Invalid Month*";
      }
    }

    private function getDateName($day, $month) {
      if ($day == 32 && $month == 7) return "Shieldmeet";

      else if ($day == 31) {
        if ($month == 1) return "Midwinter, Hammer";
        if ($month == 4) return "Greengrass, Tarsakh";
        if ($month == 7) return "Midsummer, Flamerule";
        if ($month == 9) return "High Harvestide, Eleint";
        if ($month == 11) return "Feast of the Moon, Uktar";
      }

      else if ($day == 1) {
        return "First of " . $this->getMonthName($month);
      }
      else if ($day == 2) {
        return "Second of " . $this->getMonthName($month);
      }
      else if ($day == 3) {
        return "Third of " . $this->getMonthName($month);
      }
      else if ($day == 21) {
        return $this->getMonthName($month) . " " . $day . "st";
      }
      else if ($day == 22) {
        return $this->getMonthName($month) . " " . $day . "nd";
      }
      else if ($day == 23) {
        return $this->getMonthName($month) . " " . $day . "rd";
      }
      else {
        return $this->getMonthName($month) . " " . $day . "th";
      }
    }

    private function getEFUDate() {
//      $firstdate = getdate(mktime(0,0,0,2,10,2000)); //$ctime(Feburary 10 2000 00:00:00)
      $now = getdate();
//      $secondspast = $now[0] - $firstdate[0];
	//set %currenttime $ctime
      //set %secondspast $calc(%currenttime - %firstdate)

      $date_offset = -53;

      $seconds = $now[0] - 18000 + ($date_offset * 24 * 60 * 60);  //set %nSeconds $calc($ctime - 18000)
      $daysSinceEra = floor(($seconds / 86400) - 12886);  //set %nDaysSinceEra $floor($calc((%nSeconds / 86400) - 12886))

      $year = 1375;
      $month = 1;

      while ($daysSinceEra > $this->getMonthLength($month, $year)) {
        $daysSinceEra = $daysSinceEra - $this->getMonthLength($month, $year);
        $month++;
        if ($month >= 13) {
          $month = 1;
          $year++;
        }
      }

      $adjustedYear = $year - 1222; //set %adjustedYear $calc(%nYear - 1222)

      return "::[ " . $this->getDateName($daysSinceEra, $month) . " : Year " . $adjustedYear . " : " . $year . " DR ]::";
    }
  }

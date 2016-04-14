<?php

return [
    'badminton' => '$amount = ($countOfAreas * $countOfAmHours * 20) + ($countOfAreas * $countOfPmHours * 30)',
    'pingpong' => '$amount = ($countOfPeople * $countOfAmHours * 15) + ($countOfPeople * $countOfPmHours * 20)',
    'basketball' => '$amount = $isHalf ? 300 : 600',
];

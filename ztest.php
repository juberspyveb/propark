SELECT
T.*,
np.number_plate,
c.mobile,
TIMESTAMPDIFF(
MINUTE,
T.arrival_time,
"2022-04-15 12:32:32"
) AS time_diff_in_mins,
(T.parking_time -5) AS PT,
(
TIMESTAMPDIFF(
MINUTE,
T.arrival_time,
"2022-04-15 12:32:32"
) >=(T.parking_time -5)
) AS TT
FROM
`transactions` AS `T`
LEFT JOIN `slots` AS `S`
ON
`T`.`slot_id` = `S`.`id`
LEFT JOIN `lots` AS `L`
ON
`S`.`lot_id` = `L`.`id`
LEFT JOIN `number_plats` AS `np`
ON
`T`.`number_plate_id` = `np`.`id`
LEFT JOIN `customers` AS `c`
ON
`T`.`customer_id` = `c`.`id`
WHERE
`L`.`id` = 2 AND `T`.`status` = "In-Progress" AND TIMESTAMPDIFF(MINUTE, T.arrival_time, "2022-04-15 12:32:32") >= (T.parking_time-5)
ORDER BY
`T`.`arrival_tim1e` ASC

0 => 2
1 => "In-Progress"
2 => "2022-04-15 12:32:32"
3 => "(T.parking_time-5)"



SELECT
T.*,
np.number_plate,
c.mobile,
TIMESTAMPDIFF(
MINUTE,
T.arrival_time,
"2022-04-15 12:30:12"
) AS time_diff_in_mins,
(T.parking_time -5) AS PT,
(TIMESTAMPDIFF(MINUTE, T.arrival_time,"2022-04-15 12:30:12") >= (T.parking_time-5) ) as TT
FROM
`transactions` AS `T`
LEFT JOIN `slots` AS `S`
ON
`T`.`slot_id` = `S`.`id`
LEFT JOIN `lots` AS `L`
ON
`S`.`lot_id` = `L`.`id`
LEFT JOIN `number_plats` AS `np`
ON
`T`.`number_plate_id` = `np`.`id`
LEFT JOIN `customers` AS `c`
ON
`T`.`customer_id` = `c`.`id`
WHERE
`L`.`id` = 2 AND T.status = 'In-Progress' AND TIMESTAMPDIFF(MINUTE, T.arrival_time, "2022-04-15 12:30:12") >= (T.parking_time-5)
ORDER BY
`T`.`arrival_time` ASC;
```
SELECT
    w.first_name,
    w.last_name,
    GROUP_CONCAT(DISTINCT c.name ORDER BY c.name ASC SEPARATOR ', ') AS children_names,
    car.model AS car_model
FROM
    worker w
LEFT JOIN
    child c ON w.id = c.user_id
LEFT JOIN
    car ON w.id = car.user_id
GROUP BY
    w.id, w.first_name, w.last_name, car.model;
```

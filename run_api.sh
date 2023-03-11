#!/bin/bash

echo "Press 1 to get The total count of products."
echo "Press 2 to get The total count of products per each website."
echo "Press 3 to get The average price of a product."
echo "Press 4 to get The website that has the highest total price of its products."
echo "Press 5 to get The total price of products added this month."
echo "---------------"

HOST='http://127.0.0.1:8000'
HEADER='--header "Content-Type: application/json"'

read INPUT

case $INPUT in

  1)
    result=$(curl --location --request GET "$HOST/api/statistics/products-count" $HEADER)
    echo $result
    ;;

  2)
    result=$(curl --location --request GET "$HOST/api/statistics/products-count-website" $HEADER)
    echo $result
    ;;

  3)
    result=$(curl --location --request GET "$HOST/api/statistics/products-average" $HEADER)
    echo $result
    ;;

  4)
    result=$(curl --location --request GET "$HOST/api/statistics/products-highest" $HEADER)
    echo $result
    ;;

  5)
    result=$(curl --location --request GET "$HOST/api/statistics/products-month" $HEADER)
    echo $result
    ;;

  *)
    echo -n "unknown"
    ;;
esac

read -p "Press enter to continue"
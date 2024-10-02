<?php

namespace App\Repository;

use App\Model\Sale;
use DateTime;
use MongoDB\BSON\UTCDateTime;
use DateTimeZone;



class SalesRepository extends MongoRepository
{

	public function setOneSale(Sale $sale): string
	{
		$date = new DateTime('now', new DateTimeZone('Europe/Paris'));
		$utcDate = new UTCDateTime($date->getTimestamp() * 1000);

		$result = $this->collection->insertOne([
			'id' => $sale->getId(),
			'name' => $sale->getName(),
			'genre' => $sale->getGenre(),
			'platform' => $sale->getPlatform(),
			'store' => $sale->getStore(),
			'price' => $sale->getPrice(),
			'quantity' => $sale->getQuantity(),
			'date' => $utcDate
		]);

		return $result->getInsertedId();
	}

	public function getOneSale(int $id): Sale
	{
		$result = $this->collection->findOne(['id' => $id]);
		$date = $result['date']->toDateTime();

		$sale = new Sale();
		$sale->setId($result['id']);
		$sale->setName($result['name']);
		$sale->setGenre($result['genre']);
		$sale->setPlatform($result['platform']);
		$sale->setStore($result['store']);
		$sale->setPrice($result['price']);
		$sale->setQuantity($result['quantity']);
		$sale->setDate($date);

		return $sale;
	}

	public function getAllSales(): array
	{
		$result = $this->collection->find();
		$sales = [];
		foreach ($result as $sale) {
			$date = $sale['date']->toDateTime();

			$sale = new Sale();
			$sale->setId($sale['id']);
			$sale->setName($sale['name']);
			$sale->setGenre($sale['genre']);
			$sale->setPlatform($sale['platform']);
			$sale->setStore($sale['store']);
			$sale->setPrice($sale['price']);
			$sale->setQuantity($sale['quantity']);
			$sale->setDate($date);
			$sales[] = $sale;
		}

		return $sales;
	}

	public function getAllSalesByDate(string|null $store = null): array
	{
		// Prépare le pipeline d'agrégation
		$pipeline = [];

		// Ajoute une condition de filtrage par magasin si spécifiée
		if ($store) {
			$pipeline[] = [
				'$match' => [
					'store' => $store
				]
			];
		}

		// Ajoute le groupe pour agréger les ventes par date et par jeu
		$pipeline[] = [
			'$group' => [
				'_id' => [
					'date' => [
						'$dateToString' => ['format' => '%Y-%m-%d', 'date' => '$date']
					],
					'name' => '$name',
					'platform' => '$platform',
				],
				'totalQuantity' => ['$sum' => '$quantity'],
				'stores' => ['$addToSet' => '$store']
			]
		];

		// Tri des résultats par date
		$pipeline[] = [
			'$sort' => ['_id.date' => 1]
		];

		// Exécutez l'agrégation
		$result = $this->collection->aggregate($pipeline);

		$sales = [];
		foreach ($result as $sale) {
			$sales[] = [
				'name' => $sale['_id']['name'],
				'platform' => $sale['_id']['platform'],
				'date' => $sale['_id']['date'],
				'totalQuantity' => $sale['totalQuantity'],
				'stores' => $sale['stores']
			];
		}

		return $sales;
	}

	public function getAllSalesDatas(string|null $store = null): array
	{
		// Prépare le pipeline d'agrégation
		$pipeline = [];

		// Ajoute une condition de filtrage par magasin si spécifiée
		if ($store) {
			$pipeline[] = [
				'$match' => [
					'store' => $store
				]
			];
		}

		// Ajoute le groupe pour agréger les ventes par date et par jeu
		$pipeline[] = [
			'$group' => [
				'_id' => [
					'date' => [
						'$dateToString' => ['format' => '%Y-%m-%d', 'date' => '$date']
					],
					'name' => '$name',
					'platform' => '$platform'
				],
				'totalQuantity' => ['$sum' => '$quantity'],
				'totalPrice' => ['$sum' => ['$multiply' => ['$price', '$quantity']]],
				'stores' => ['$addToSet' => '$store']
			]
		];

		// Tri des résultats par date
		$pipeline[] = [
			'$sort' => ['_id.date' => 1]
		];

		// Exécutez l'agrégation
		$result = $this->collection->aggregate($pipeline);

		$sales = [];
		foreach ($result as $sale) {
			$sales[] = [
				'name' => $sale['_id']['name'],
				'platform' => $sale['_id']['platform'],
				'date' => $sale['_id']['date'],
				'totalQuantity' => $sale['totalQuantity'],
				'price' => $sale['totalPrice'],
				'stores' => $sale['stores']
			];
		}

		return $sales;
	}
}

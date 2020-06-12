<?php

interface IDataManager {
	public static function getCategories() : array;
	public static function getArticlesByCategory(int $categoryId) : array;
	public static function getUserById(int $userId);
	public static function getUserByUserName(string $userName);
	public static function addArticle(int $user, int $categoryId, string $title, string $subtitle, string $text);
}
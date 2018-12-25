<?php

namespace OC\UserBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

class UserRepository extends \Doctrine\ORM\EntityRepository
{
	 
  // pour la pagination
  public function getUsers($page, $nbPerPage)
  {
	if(is_numeric($page)) {
		$query = $this->createQueryBuilder('a')
		  ->orderBy('a.id', 'DESC')
		  ->getQuery()
		;

		$query
		  // On définit l'annonce à partir de laquelle commencer la liste
		  ->setFirstResult(($page-1) * $nbPerPage)
		  // Ainsi que le nombre d'annonce à afficher sur une page
		  ->setMaxResults($nbPerPage)
		;

		// Enfin, on retourne l'objet Paginator correspondant à la requête construite
		// (n'oubliez pas le use correspondant en début de fichier)
		return new Paginator($query, true);
	}
  }

}

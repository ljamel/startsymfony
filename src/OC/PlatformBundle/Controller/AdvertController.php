<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Team;
use OC\PlatformBundle\Entity\Category;
use OC\PlatformBundle\Entity\Friends;
use OC\PlatformBundle\Entity\Comment;
use OC\PlatformBundle\Repository\AdvertRepository;
use OC\UserBundle\Entity\Messages;
use OC\PlatformBundle\Form\CommentType;
use OC\PlatformBundle\Form\AdvertEditType;
use OC\PlatformBundle\Form\AdvertType;
use OC\PlatformBundle\Form\CkeditorType;
use OC\PlatformBundle\Form\MessageType;
use OC\PlatformBundle\Form\TeamType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Dwr\AvatarBundle\Model\AvatarFactory;
use Dwr\AvatarBundle\Model\PlainAvatar;
use Dwr\AvatarBundle\Model\ProfileAvatar;


class AdvertController extends Controller
{
  public function indexAction($page)
  {
	$title = $page;
    $nbPerPage = 3;

    // On récupère notre objet Paginator
    $listAdverts = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Advert')
      ->getAdverts($page, $nbPerPage)
    ;

    // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
    $nbPages = ceil(count($listAdverts) / $nbPerPage);

    // Si la page n'existe pas, on retourne une 404
    if ($page > $nbPages) {
      throw $this->createNotFoundException("La page ".$page." n'existe pas.");
    }

	// Récupération des AdvertSkill de l'annonce
    $listAdvertSkills = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Category')
      ->findAll()
    ;	
	  
	// Pour les meta tags et titre - - ----------
	$metas = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Advert')
      ->findOneBy(array(), array('id' => 'desc'))
    ;
	  
	$index = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Advert')
      ->findOneBy(array('id' => 1), array('id' => 'desc'))
    ;
	  
    // On donne toutes les informations nécessaires à la vue
    return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
      'listAdverts' => $listAdverts,
      'listAdvertSkills' => $listAdvertSkills,
      'nbPages'     => $nbPages,
      'page'        => $page,
      'metatag'     => $metas,
      'metatag'     => $metas,
	  'index'		=> $index,
    ));
  }

  public function commentAction(Request $request, $id) {
  	
    $advertid = new  Comment();
    $form   = $this->get('form.factory')->create(CommentType::class, $advertid);
	

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
	  $advertid->setAdvertid(8);
      $em->persist($advertid);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Commentaire envoyé.');

    }

    return $this->render('OCPlatformBundle:Advert:comment.html.twig', array(
      'form' => $form->createView(),
    ));
  }
	
  public function categoryAction($name)
  {
	  
	$adverts = $this
	  ->getDoctrine()
	  ->getManager()
	  ->getRepository('OCPlatformBundle:Advert')
	;

	$graphisme = array('cat' => $name);
	  
	$adverts = $adverts->getAdvertWithCategories($graphisme);
	  
	// Récupération des AdvertSkill de l'annonce
    $listAdvertSkills = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Category')
      ->findAll()
    ;

    return $this->render('OCPlatformBundle:Advert:categories.html.twig', array(
      'adverts'           => $adverts,
      'listAdvertSkills'           => $listAdvertSkills,
    ));
  }
	
  public function translationAction($name)
  {
	// On récupère le service translator
	$translator = $this->get('translator');

	// Pour traduire dans la locale de l'utilisateur :
	$texteTraduit = $translator->trans('Mon message à inscrire dans les logs');
    return $this->render('OCPlatformBundle:Advert:translation.html.twig', array(
      'name' => $name
    ));
  }	
	
   /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function adminAction()
  {
    // verifi si le visiteur est connecter sinon sa renvoi à la page /login
	$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
	// Pour récupérer le service UserManager du bundle
	$userManager = $this->get('fos_user.user_manager');
	  
	// Pour récupérer la liste de tous les utilisateurs
    $users = $userManager->findUsers();


    $nbPerPage = 3;
    $page = 1;

    $listAdverts = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Advert')
      ->getAdverts(1, $nbPerPage)
    ;
	  
    // Récupération des AdvertSkill de l'annonce
    $comments = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Comment')
      ->findBy(array('published' => 1))
    ;

    // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
    $nbPages = ceil(count($listAdverts) / $nbPerPage);

    // On donne toutes les informations nécessaires à la vue
    return $this->render('OCPlatformBundle:Advert:admin.html.twig', array(
      'listAdverts' => $listAdverts,
      'nbPages'     => $nbPages,
      'page'        => $page,
      'users'       => $users,
	  'comments'    => $comments, 
    ));
  }
	
  public function userAction(Request $request, $user){
	  
	// verifi si le visiteur est connecter sinon sa renvoi à la page /login
	$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  	// Pour récupérer le service UserManager du bundle
	$userManager = $this->get('fos_user.user_manager');
	  	  
	// récupérer l'utilisateur courant
	$user=$this->getUser();
	$advert = $userManager->findUserBy(array('id' => $user->getId()));

    $nbPerPage = 3;
    $page = 1;
  
	$bdd = $this->getDoctrine()->getManager();
	  
    $listAdverts = $bdd->getRepository('OCPlatformBundle:Advert')->getAdverts(1, $nbPerPage);
	  
	$link = $bdd->getRepository('OCPlatformBundle:Friends')->findBy(array('userid' => $user->getId()));
	  
	$linkwaitings = $bdd->getRepository('OCPlatformBundle:Friends')->findBy(array('friendswaitingid' => 3));
	
	$teamview = $bdd->getRepository('OCPlatformBundle:Advert')->findOneBy(array('team' => $user->getUsername()));
	  
	if(empty($teamview)) {
		$teamviewusers = 'ok';
	} else {
		$teamviewusers = $bdd->getRepository('OCPlatformBundle:Team')->findBy(array('advertid' => $teamview->getSlug())); 	
	}
	
	$teamviewusersall = $bdd->getRepository('OCPlatformBundle:Advert')->findBy(array('isteam' => true)); 	
	  
	$metas = $bdd
      ->getRepository('OCPlatformBundle:Advert')
      ->findBy(array('author' => $user), array('id' => 'desc'))
    ;
	  
	  
	$messages = $bdd->getRepository('OC\UserBundle\Entity\Messages')->findBy(array('userreceived' => $user->getId()));// ______
	 
	// Servira de lien entre le groupe et l'article
	$random = random_bytes(15);
	 $random2 = sha1($random);
	  
	$teamc = new Advert();
	$advertlink = new Team();
    $form   = $this->get('form.factory')->create(AdvertType::class, $teamc);
    $this->get('form.factory')->create(TeamType::class, $advertlink);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
		
	  // pour le lien entre le contenue est le groupe
      $em = $this->getDoctrine()->getManager();
	  $advertlink->setUserid($user->getUsername());
	  $advertlink->setGradesid('master');
	  $advertlink->setFriendswaitingid(1);
	  $advertlink->setAdvertid($random2);
	  
	  // contenue du groupe
	  $teamc->setTeam($user);
	  $teamc->setAuthor($user->getUsername());
	  $teamc->setIsteam(true);
	  $teamc->setSlug($random2);
      $em->persist($teamc);
      $em->persist($advertlink);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Team crée.');
	  return $this->redirect($request->getUri());
    }

	  
	// Les amis déjà accepter, méthode multi critères
	$friendsallow = $bdd->getRepository('OCPlatformBundle:Friends')->findBy(array('userid' => $user->getId(), 'friendswaitingid' => 1));
	  
    // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
    $nbPages = ceil(count($listAdverts) / $nbPerPage);
	  
	
    $linkwaitingsnb = ceil(count($linkwaitings));
	  
	// Le nombre d'amis
	$nbfriends = ceil(count($friendsallow));

	$avatar = new AvatarFactory();
    $profileAvatar = $avatar->generate(new ProfileAvatar(140, 140)); 
	$image = 'images/' . $user->getUsername() . 'avatar.jpg';
	if(file_exists($image)) {
		$gravatar = $image;
	} else {
		$gravatar = $profileAvatar->save('', $image);
	}
	  
	  
    // On donne toutes les informations nécessaires à la vue
    return $this->render('OCPlatformBundle:Advert:user.html.twig', array(
      'listAdverts' => $listAdverts,
      'links'       => $link,
      'linkwaitings'=> $linkwaitings,
      'linkwaitingsnb'=> $linkwaitingsnb,
      'friendsallow'=> $friendsallow,
      'nbPages'     => $nbPages,
      'page'        => $page,
      'user'        => $advert,
      'nbfriends'   => $nbfriends,
	  'messages'	=> $messages,
      'profileAvatar'        => $gravatar,
	  'form' 		=> $form->createView(),
	  'teamview'    => $teamview,
	  'teamviewusers' => $teamviewusers,
	  'teamviewusersall' => $teamviewusersall,
      
    ));
  }

   /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function deleteuserAction($user) {
	  
    // Pour récupérer le service UserManager du bundle
	$userManager = $this->get('fos_user.user_manager');
  	// Pour supprimer un utilisateur
	// Pour charger un utilisateur
	$userr = $userManager->findUserBy(array('username' => $user));
	$userManager->deleteUser($userr);
	  
	return $this->redirectToRoute('oc_platform_admin', array());
  }  
  
  public function deletecommentAction(Request $request, $id) {
  		  
    $em = $this->getDoctrine()->getManager();

    $advert = $em->getRepository('OCPlatformBundle:Comment')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("Le commentaire d'id ".$id." n'existe pas.");
    }else {
      $em->remove($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', "Le commentaire a bien été supprimé.");

      return $this->redirectToRoute('oc_platform_admin');
    }
	  
	return $this->redirectToRoute('oc_platform_admin', array());
  }
  
  public function searchinguserAction(Request $request) {
	  
	// Pour récupérer le service UserManager du bundle ENFIN çA FONCTIONNE LA RECHERCHE
	$userManager = $this->get('fos_user.user_manager');
	  if(empty($_POST['find'])){
	  	$_POST['find'] = $find;
	  }
	  	  
	  $user = $userManager->findUserBy(
	    array('username' => htmlspecialchars($_POST['find'])), // Critere
	    array('date' => 'desc'),  // Tri
	    50,                       // Limite
	    0                         // debut
	  );
	  
	  if(empty($user)){

		// On ajoute un message flash arbitraire
		$request->getSession()->getFlashBag()->add('info', 'Aucun utilisateur trouvé');
	  	return $this->redirectToRoute('oc_platform_home', array());
	  }
	  
	  if(empty($user)){ 
		 // On ajoute un message flash arbitraire
		$request->getSession()->getFlashBag()->add('info', 'Aucun utilisateur trouvé');
	  	return $this->redirectToRoute('oc_platform_home', array());
	  }
	  
	return $this->render('OCPlatformBundle:Advert:searchinguser.html.twig', array(
      'user'        => $user,
    ));
  }
	
  public function finduserAction($find, Request $request) {
	  
	// Pour récupérer le service UserManager du bundle ENFIN çA FONCTIONNE LA RECHERCHE
	$userManager = $this->get('fos_user.user_manager');
	  	  
	  $user = $userManager->findUserBy(
	    array('id' => htmlspecialchars($find)), // Critere
	    array('date' => 'desc'),  // Tri
	    50,                       // Limite
	    0                         // debut
	  );

	  
	  if(empty($user)){ 
		 // On ajoute un message flash arbitraire
		$request->getSession()->getFlashBag()->add('info', 'Aucun utilisateur trouvé');
	  	return $this->redirectToRoute('oc_platform_home', array());
	  }
	  
	return $this->render('OCPlatformBundle:Advert:searchinguser.html.twig', array(
      'user'        => $user,
    ));
  }

	
	// A finir pour pouvoir ajouter des amies ---------------------------------------------
	public function addfriendAction(Request $request, $id) {
		
		$em = $this->get('fos_user.user_manager');
		
		// récupérer l'utilisateur courant
		$user=$this->getUser();
		$userc = $em->findUserBy(array('id' => $user->getId()));
		
		// demande d'ajout d'amie
		$friend = $em->findUserBy(array('id' => $id));
	
		$friends = new Friends();
		
		// pour l'entiter friendswaiting 11+=attente, 1=accepter, 0=refuser.
		$friends->setUserid($userc->getId());
		$friends->setFriendsid($friend->getId());		
		$friends->setFriendswaitingid(3);		

		// connection en base de donnée
		$bdd = $this->getDoctrine()->getManager();
		$link = $bdd->getRepository('OCPlatformBundle:Friends')->findBy(array('friendswaitingid' => 3));
		
	    // préparer pour l'envoi en base de donnée
		$bdd->persist($friends);
		// permet de toutes envoyer en base de donnée
		if(3 < $link) {
			$bdd->flush();
			$request->getSession()->getFlashBag()->add('info', "Demande d'amie effectuer.");
			return $this->redirectToRoute('oc_platform_home');	
		}
		
		$request->getSession()->getFlashBag()->add('info', "Demande d'amie déjà effectuer.");
		return $this->redirectToRoute('oc_platform_home');
	}

  public function messageboxAction(Request $request, $id) {
  		// verifi si le visiteur est connecter sinon sa renvoi à la page /login
	$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  	// Pour récupérer le service UserManager du bundle
	$userManager = $this->get('fos_user.user_manager');
	  	  
	// récupérer l'utilisateur courant
	$user=$this->getUser();

    $nbPerPage = 3;
    $page = 1;
  
	$bdd = $this->getDoctrine()->getManager();
	  
	// ajouter des critèrs de séléctions pour la sécurité et afficher un message a la fois
	$messages = $bdd->getRepository('OC\UserBundle\Entity\Messages')->findBy(array('userreceived' => $user->getId(), 'id' => $id));
	  
    $nbPages = ceil(count($messages) / $nbPerPage);
	  

    // On donne toutes les informations nécessaires à la vue
    return $this->render('OCPlatformBundle:Advert:messagebox.html.twig', array(
      'nbPages'     => $nbPages,
      'page'        => $page,
	  'messages'	=> $messages,
    ));
  }
	
  // Gestion messagerie interne ----------------------------------------------------------------------------------lll
  public function postprivateAction(Request $request, $id) {
	
	// verifi si le visiteur est connecter sinon sa renvoi à la page /login
	$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
	 
  	$em = $this->get('fos_user.user_manager');
		
	// récupérer l'utilisateur courant
	$user=$this->getUser();

	echo $user->getId();
	echo $id;
	echo 'ohniobj';
	
	// changer l'entiter advert pour envoyer dans la bonne table
	$advert = new Messages();
    $form   = $this->get('form.factory')->create(MessageType::class, $advert);
	$form->remove('categories');
	$form->remove('image');
	

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
	  $advert->setUserreceived($id);
	  $advert->setAuthor($user->getId());
      $em = $this->getDoctrine()->getManager();
      $em->persist($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', 'Message envoyé.');
 	  return $this->redirectToRoute('oc_platform_postprivate', array(
      'form' => $form->createView(),
	  'id'   => $id,
    ));
    }

    return $this->render('OCPlatformBundle:Advert:message.html.twig', array(
      'form' => $form->createView(),
    ));
	  
	  
  }
	
  public function viewAction(Advert $advert, Request $request)
  {
	
    $em = $this->getDoctrine()->getManager();

	$this->get('fos_user.user_manager');		
	// récupérer l'utilisateur courant
	$user=$this->getUser();
	  
    $listApplications = $em
      ->getRepository('OCPlatformBundle:Application')
      ->findBy(array('advert' => $advert))
    ;

    $listAdvertSkills = $em
      ->getRepository('OCPlatformBundle:AdvertSkill')
      ->findBy(array('advert' => $advert))
    ;    
	
    // Pour les meta tags et titre - - ----------
	$metas = $em
      ->getRepository('OCPlatformBundle:Advert')
      ->findOneBy(array('slug' => $advert->getSlug()), array('id' => 'desc'))
    ;
	
		
    $comments = $em
      ->getRepository('OCPlatformBundle:Comment')
      ->findBy(array('title' => $metas->getSlug()))
    ;
	
	$advertid = new  Comment();
    $form   = $this->get('form.factory')->create(CommentType::class, $advertid);	

    if ($request->isMethod('POST') && $form->handleRequest($request)) {
      $em = $this->getDoctrine()->getManager();
	  $advertid->setAdvertid(3);
	  $advertid->setAuthor('anonymous');
	  $advertid->setTitle($metas->getSlug());
		
		
	
      $em->persist($advertid);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Commentaire envoyé.');
	  return $this->redirect($request->getUri());

    }    
	

    return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
      'advert'           => $advert,
      'listApplications' => $listApplications,
      'listAdvertSkills' => $listAdvertSkills,
	  'metatag'			 => $metas,
	  'comments'		 => $comments,
	  'form' 			 => $form->createView(),
    ));
  }

  /**
   * @Security("has_role('ROLE_AUTEUR')")
   */
  public function addAction(Request $request)
  {
	$this->get('fos_user.user_manager');		
	// récupérer l'utilisateur courant
	$user=$this->getUser();

    $advert = new Advert();
    $form   = $this->get('form.factory')->create(AdvertType::class, $advert);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
	  $advert->setAuthor($user->getUsername());
      $em->persist($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      return $this->redirectToRoute('oc_platform_view', array('slug' => $advert->getSlug()));
    }

    return $this->render('OCPlatformBundle:Advert:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }  
	
   /**
   * @Security("has_role('ROLE_AUTEUR')")
   */
  public function bioAction(Request $request)
  {
	$this->get('fos_user.user_manager');		
	// récupérer l'utilisateur courant
	$user=$this->getUser();

    $advert = new Advert();
    $form   = $this->get('form.factory')->create(AdvertType::class, $advert);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
	  $advert->setAuthor($user->getUsername());
      $em->persist($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

      return $this->redirectToRoute('oc_platform_view', array('slug' => $advert->getSlug()));
    }

    return $this->render('OCPlatformBundle:Advert:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }

    /**
     * Displays a form to edit an existing Users.
     * @Security("has_role('ROLE_MODERATEUR')")
     */
  public function editAction($slug, Request $request)
  {
	  
	$this->get('fos_user.user_manager');		
	// récupérer l'utilisateur courant. Pour donner l'autorisation d'edition q'a l'editeur de l'article
	$user=$this->getUser();
	  
    $em = $this->getDoctrine()->getManager();

    $advert = $em->getRepository('OCPlatformBundle:Advert')->findOneBy(array('slug' => $slug));

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$slug." n'existe pas.");
    }

    $form = $this->get('form.factory')->create(AdvertEditType::class, $advert);

	$advert->getAuthor();
	  
	  
    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() && $user == $advert->getAuthor()) {
      // Inutile de persister ici, Doctrine connait déjà notre annonce
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      return $this->redirectToRoute('oc_platform_view', array('slug' => $advert->getSlug()));
    }

    return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert,
      'form'   => $form->createView(),
    ));
  }

  public function deleteAction(Request $request, $id)
  {
    $em = $this->getDoctrine()->getManager();

    $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'annonce contre cette faille
    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em->remove($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

      return $this->redirectToRoute('oc_platform_home');
    }
    
    return $this->render('OCPlatformBundle:Advert:delete.html.twig', array(
      'advert' => $advert,
      'form'   => $form->createView(),
    ));
  }

  public function menuAction($limit)
  {
    $em = $this->getDoctrine()->getManager();

    $listAdverts = $em->getRepository('OCPlatformBundle:Advert')->findBy(
      array(),                 // Pas de critère
      array('date' => 'desc'), // On trie par date décroissante
      $limit,                  // On sélectionne $limit annonces
      0                        // À partir du premier
    );

    return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
      'listAdverts' => $listAdverts
    ));
  }

  // Méthode facultative pour tester la purge
  public function purgeAction($days, Request $request)
  {
    // On récupère notre service
    $purger = $this->get('oc_platform.purger.advert');

    // On purge les annonces
    $purger->purge($days);

    // On ajoute un message flash arbitraire
    $request->getSession()->getFlashBag()->add('info', 'Les annonces plus vieilles que '.$days.' jours ont été purgées.');

    // On redirige vers la page d'accueil
    return $this->redirectToRoute('oc_platform_home');
  }
}

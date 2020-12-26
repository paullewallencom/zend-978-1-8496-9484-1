<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
	DAO\Db\DTO\Cards,
	Zend\View\Model\ViewModel;

class DaoController extends AbstractActionController
{
    public function indexAction()
	{
		// Get the cards mapper
		$cards = $this->getServiceLocator()
				      ->get('DAO_Mapper_Cards');
		
		// Get all the cards
		$allCards = $cards->getAll();
		
		// We will receive an array with DAO\Db\DTO\Cards objects
		foreach ($allCards as $card) {
			\Zend\Debug\Debug::dump(array(
				'Id' => $card->getId(),
				'Color' => $card->getColor(),
				'Type' => $card->getType(),
				'Value' => $card->getValue(),
			), 'GetAll - ');
		}
		
		// Now load one specific card
		$card = $cards->load(12);
		
		\Zend\Debug\Debug::dump(array(
			'Id' => $card->getId(),
			'Color' => $card->getColor(),
			'Type' => $card->getType(),
			'Value' => $card->getValue(),
		), 'Load(12) - ');
		
		// Inserting a card
		$pk = $cards->insert(new Cards(
				'picture',
			    'Goblin',
				'diamond'
		));
		
		\Zend\Debug\Debug::dump($pk, 'Insert - ');
		
		// Now edit a card!
		\Zend\Debug\Debug::dump(
				$cards->update(new Cards(
				'picture',
			    'Person',
				'heart',
						
				// We are now setting an id to the card.
				$pk
		)), 'Update - ');
		
		// Finally, delete a card
		\Zend\Debug\Debug::dump($cards->delete($pk), 'Delete - ');
		
		return new ViewModel();
	}
}

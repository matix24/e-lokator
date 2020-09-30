<?php 


namespace App\Twig\Layout;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;


class CheckerPath extends AbstractExtension {
    
    public function getFunctions(){
        return [
            new TwigFunction('aLinkIsActive', [$this, 'aLinkIsActive']),
            new TwigFunction('menuIsOpen', [$this, 'menuIsOpen']),
            new TwigFunction('menuItemIsActive', [$this, 'menuItemIsActive']),
        ];
    }// end getFunctions

    /**
     * Funkcja sprawdza route i jeżeli się zgadza zwraca aktywną klasę potrzebną do otwarcia menu
     * 
     * @param string $pathInfo Path from Request
     * @param string $pathTarget Path target
     * @return string Name of class css
     */
    public function menuItemIsActive(string $pathInfo, string $pathTarget){
        if($pathInfo === $pathTarget){
            return 'active';
        }
        return '';
    }// end menuItemIsActive

    /**
     * @param string $pathInfo Path from Request
     * @param string $partOfRouteName Część requesta
     * @return string Name of class css
     */
    public function menuIsOpen(string $pathInfo, string $partOfRouteName){
        if(strpos($pathInfo, $partOfRouteName) !== false){
            return 'menu-open';
        }
        return '';
    }// end menuIsOpen

    /**
     * @param string $pathInfo Path from Request
     * @param string $partOfRouteName Część requesta
     * @return string Name of class css
     */
    public function aLinkIsActive(string $pathInfo, string $partOfRouteName){
        if(strpos($pathInfo, $partOfRouteName) !== false){
            return 'active';
        }
        return '';
    }// end menuIsOpen

}// end class
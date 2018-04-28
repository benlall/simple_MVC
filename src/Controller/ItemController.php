<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace Controller;

use Model\Item;
use Model\ItemManager;

/**
 * Class ItemController
 *
 */
class ItemController extends AbstractController
{

    /**
     * Display item listing
     *
     * @return string
     */
    public function index()
    {
        $itemManager = new ItemManager();
        $items = $itemManager->selectAll();

        if (isset($_SESSION['message'])) {
            $messageSession = $_SESSION['message'];
            $_SESSION['message'] = null;
        } else {
            $messageSession = null;
        }

        $templateVariables = ['items' => $items, 'messageSession' => $messageSession];

        return $this->twig->render('Item/index.html.twig', $templateVariables);
    }

    /**
     * Display item informations specified by $id
     *
     * @param  int $id
     *
     * @return string
     */
    public function show(int $id)
    {
        $itemManager = new ItemManager();
        $item = $itemManager->selectOneById($id);

        return $this->twig->render('Item/show.html.twig', ['item' => $item]);
    }

    /**
     * Display item edition page specified by $id
     *
     * @param  int $id
     *
     * @return string
     */
    public function edit(int $id)
    {
        // TODO : edit item with id $id
        if ($_POST){
            $datas = [];
            $datas['title'] = $_POST['title'];

            $itemManager = new ItemManager();
            $itemManager->update($id, $datas);


            $_SESSION['message'] = 'Mise à jour OK';

            header ('Location: /');
            die;

        }
        return $this->twig->render('Item/edit.html.twig', ['item', $id]);
    }

    /**
     * Display item creation page
     *
     * @return string
     */
    public function add()
    {
        if ($_POST) {
            $datas = [];
            $datas['title'] = $_POST['title'];

            $itemManager = new ItemManager();
            $itemManager->insert($datas);

            $_SESSION['message'] = 'Insertion OK';
            header ('Location: /');
            die;
        }

        return $this->twig->render('Item/add.html.twig');
    }

    /**
     * Display item delete page
     *
     * @param  int $id
     *
     * @return string
     */
    public function delete(int $id)
    {

        // TODO : delete the item with id $id
        $itemManager = new ItemManager();
        $itemManager->delete($id);

        $_SESSION['message'] = 'Suppression OK';

        header ('Location: /');
        die;

    }

    public function search()
    {
        $itemManager = new ItemManager();
        $query = $_GET['query'] ?? null ;

        if (!$query) {

            $templateVariables = ['items' => $itemManager->selectAll()];

            return $this->twig->render('Item/search.html.twig', $templateVariables);
        }

        $templateVariables = ['items' => $itemManager->searchItem($query)];

        return $this->twig->render('Item/search.html.twig', $templateVariables);
    }
}

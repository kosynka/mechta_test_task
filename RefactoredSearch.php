<?php

namespace VBulletin\Search;

use VBulletin\Log\LoggerInterface;
use VBulletin\Render\Render;
use Exception;
use PDO;
use VBulletin\Log\DatabaseInterface;

class RefactoredSearch
{
    private $db;
    private $logger;

    public function __construct(private Render $renderer)
    {
        $this->logger = LoggerInterface::getInstance();
        $this->db = DatabaseInterface::getInstance();
    }

    public function doSearch(): void
    {
        $action = $this->getSearchAction();

        if ($action === 'process') {
            $this->processSearch();
        } elseif ($action === 'showresults') {
            $this->showSearchResults();
        } else {
            $this->renderer->renderSearchForm();
        }
    }

    private function getSearchAction(): string
    {
        if (!empty($_REQUEST['searchid'])) {
            return 'showresults';
        } elseif (!empty($_REQUEST['q'])) {
            return 'process';
        }

        return '';
    }

    private function processSearch(): void
    {
        $table = 'vb_post';
        $query = (string)$_REQUEST['q'];

        $sth = $this->db->prepare("SELECT * FROM {$table} WHERE text LIKE %:query%");
        $sth->bindParam(':query', $query, PDO::PARAM_STR);

        try {
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);

            $this->logger->channel('search')->info($query);

            $this->renderer->renderSearchResults($result);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }

    private function showSearchResults(): void
    {
        $table = 'vb_searchresult';
        $searchId = (int)$_REQUEST['searchid'];

        $sth = $this->db->prepare("SELECT * FROM {$table} WHERE searchid = :searchid");
        $sth->bindParam(':searchid', $searchId, PDO::PARAM_INT);

        try {
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);

            $this->renderer->renderSearchResults($result);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}

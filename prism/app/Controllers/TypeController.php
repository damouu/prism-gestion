<?php

namespace PrismGestion\Controllers;

use PrismGestion\Models\Materiel;

class TypeController extends Controller
{

    public function getAll($request, $response, $args)
    {
        /** TODO
         * recuperation de tous
         * filtres
         */
    }

    public function getOne($request, $response, $args) {
        /** TODO
         *  recuperation d'un id
         *  affcichage de cet id
         */
    }

    public function delete($request, $response, $args) {
        /** TODO
         *  suppression (softdelete) d'un element id
         */
    }

    public function put($request, $response, $args) {
        /** TODO
         *  ajout si inexistant
         *  modification si existant avec données fournies
         */
    }

    public function post($request, $response, $args) {
        /** TODO
         *  ajout d'un element
         */
    }

}
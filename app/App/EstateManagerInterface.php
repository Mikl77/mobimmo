<?php


namespace App\App;


use App\Models\All_files;
use App\Models\City;
use App\Models\Estate;
use App\Models\FrPostalCode;
use App\Models\In_Charge;
use App\Models\User;
use Faker\Factory;
use Psr\Http\Message\ServerRequestInterface;

class EstateManagerInterface
{
    //recupere tous les biens geres par l utilisateur connecte
    public function getAllManagedEstates(Int $id){
       return Estate::leftjoin('in_charge','estate.estate_id','=','in_charge.estate_id')
                    ->leftjoin('estate_status','estate_status_id','=','estate_status.id')
                    ->leftjoin('city','city_id','=','city.id')
                    ->leftjoin('client','estate.estate_client_id','=','client.id')
                    ->where('in_charge.user_id','=',$id)
                    ->get();
    }

    //recupere tous les biens de la bdd
    public function getAllEstate(){
        return Estate::get();
    }

    //recupere les 3 derniers biens cree
    public function getNewestEstates(){
        return Estate::join('estate_status','estate_status_id','=','estate_status.id')
            ->join('estate_type','estate_type_id','=','estate_type.id')
            ->latest('estate.created_at')
            ->limit(3)
            ->get();
    }

    //recupere les biens en aleatoire le nombre de biens a recuperer est a preciser
    public function getRandomEstate($limit){
        return Estate::inRandomOrder()
            ->limit($limit)
            ->get();
    }

    //recupere un seul bien pour affichage en page single property par exemple
    public function getSingleEstate($reference){
        return Estate::where('estate_reference','=',$reference)
                        ->leftjoin('estate_status','estate_status_id','=','estate_status.id')
                        ->leftjoin('estate_type','estate_type_id','=','estate_type.id')
                        ->leftjoin('parking_type','estate_parking_type_id','=','parking_type.id')
                        ->leftjoin('building_year','estate_building_year_id','=','building_year.id')
                        ->first();
    }

    //determine qui gere le bien a partir de la reference du bien
    public function getWhoManage($reference){
        $estate_id = Estate::where('estate_reference','=',$reference)->first();

        $manager_id = In_Charge::where('estate_id','=',$estate_id->estate_id)->first();

        return User::where('users.id','=',$manager_id->user_id)->first();
    }

    public function Estate_paginate($per_page,ServerRequestInterface $request){
        return Estate::paginate($per_page)->appends($request->getQueryParams());
    }

    public function getSoldEstates(){
        return Estate::where('estate_status_id','=','4')
                        ->orderBy('updated_at','desc')
                        ->limit(8)
                        ->get();
    }

    public function getAllPicturesEstate($reference){
        $estate_id = Estate::where('estate_reference','=',$reference)->first();
        return All_files::where('file_type_id','=',1)
                        ->where('estate_id','=',$estate_id->estate_id)
                        ->get();
    }
}
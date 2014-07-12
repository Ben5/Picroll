<?php

namespace Site\Components;

use Picroll\SiteConfig;
use Reverb\System\ComponentBase;

class Friends extends ComponentBase
{
    private $minimumSearchTermLength = 3;

    protected function
    RequiresAuthentication()
    {
        return true;
    }

    protected function
    Index($params)
    {
        $friendModel = $this->GetDependencyContainer()->GetInstance('FriendModel');
        $userId      = $_SESSION['user_id'];

        $allFriends        = $friendModel->GetAllFriendsByUserId($userId);
        $allFriendRequests = $friendModel->GetAllFriendRequestsByUserId($userId);
        $allPendingFriends = $friendModel->GetAllRequestedFriendsByUserId($userId);

        $this->ExposeVariable('friends',  $allFriends); 
        $this->ExposeVariable('requests', $allFriendRequests); 
        $this->ExposeVariable('pending',  $allPendingFriends); 
    }

    protected function
    SearchForFriend($params)
    {
        $searchTerm = $params['searchTerm'];

        $searchResult = array();
        if (strlen($searchTerm) >= $this->minimumSearchTermLength) {
            $userModel    = $this->GetDependencyContainer()->GetInstance('UserModel');
            $friendModel  = $this->GetDependencyContainer()->GetInstance('FriendModel');
            $userId       = $_SESSION['user_id'];

            $searchResult    = $userModel->SearchUsersByNameOrEmail($params['searchTerm']);
            $existingFriends = $friendModel->GetAllFriendsByUserId($userId);
            $friendRequests  = $friendModel->GetAllRequestedFriendsByUserId($userId);

            // remove the existing friends and requests from the search result
            $searchResult = array_diff($searchResult, $existingFriends);
            //var_dump($searchResult);
            $searchResult = array_diff($searchResult, $friendRequests);
            //var_dump($searchResult);
        }

        $this->ExposeVariable('result', $searchResult);
    }

    protected function
    SendFriendRequest($params)
    {
        $userId = $_SESSION['user_id'];

        $friendRequestModel = $this->GetDependencyContainer()->GetInstance('FriendRequestModel');
        $success = $friendRequestModel->CreateNewFriendRequest($userId, $params['friendId']);

        $this->ExposeVariable('result', $success);
    }
    
    protected function
    AcceptFriendRequest($params)
    {
        $userId   = $_SESSION['user_id'];
        $friendId = $params['friendId'];

        $friendModel = $this->GetDependencyContainer()->GetInstance('FriendModel');
        $success = $friendModel->AddNewFriend($userId, $friendId);

        if ($success !== false) {
            $friendRequestModel = $this->GetDependencyContainer()->GetInstance('FriendRequestModel');
            $success = $friendRequestModel->DeleteRequest($userId, $friendId);
        }

        $this->ExposeVariable('result', $success);
    }
}

$(document).ready(function() {
    //
    // global vars
    //
    var minSearchLength = 2;

    var $searchResultContainer = $('#searchResult');
    var $friendRequestList     = $('#friendRequestList');

    //
    // Page Initialisation
    //

    // disable the button by default
    $('#searchSubmit').attr('disabled', 'disabled');


    //
    // Handler declarations
    //

    // click handler for all buttons that end up in the search result container.
    $searchResultContainer.delegate('div.friendResult button', 'click', SearchResultClickHandler);

    // click handler for all buttons that end up in the friend request list
    $friendRequestList.delegate('button', 'click', FriendRequestClickHandler);
    
    // And show it when enough characters have been entered
    $('#friendSearch').on('keyup', FriendSearchKeyupHandler);

    // Search button handler
    $('#searchSubmit').on('click', SubmitSearchClickHandler);

    //
    // Event Handlers
    //

    // Send a friend request to the clicked person
    function SearchResultClickHandler() 
    {
        var friendId = $(this).data('friendid');

        $.ajax({
            url      : '/picroll/json/friends/sendFriendRequest', 
            data     : { friendId: friendId },
            dataType : "json",
            context  : $(this),
            success  :  SendFriendRequestCB
        });
    }

    // Accept the clicked friend request
    function FriendRequestClickHandler() 
    {
        var friendId = $(this).data('friendid');

        $.ajax({
            url      : '/picroll/json/friends/acceptFriendRequest', 
            data     : { friendId: friendId },
            dataType : "json",
            context  : $(this),
            success  : AcceptFriendRequestCB
        });
    }

    // Disable the search button until enough charactes have been entered
    function FriendSearchKeyupHandler(event) 
    {
        var inputLength = $(this).val().length;
        if(inputLength <= minSearchLength)
        {
            $('#searchSubmit').attr('disabled', 'disabled');
        }
        else
        {
            $('#searchSubmit').attr('disabled', false);
        }
    }

    // Search for a friend based on entered text
    function SubmitSearchClickHandler() 
    {
        var searchVal = $('#friendSearch').val();
        if(searchVal.length <= minSearchLength)
        {
            console.log('enter more characters');
            return false;
        }

        $searchResultContainer.empty();
        
        $.ajax({
            url      : '/picroll/json/friends/searchForFriend', 
            data     : { searchTerm: searchVal },
            dataType : "json",
            success  : SearchForFriendCB
        });
    }

    
    //
    // Callbacks
    //

    // Handle the results of sending a friend request
    function SendFriendRequestCB(data) 
    {
        var $searchResult = $('#searchResult');
        var friendId      = $(this).data('friendid');
        var friendName    = $(this).data('friendname');

        $searchResult.find('div.friendResult[data-friendid="'+friendId+'"]').fadeOut(400, function() {
            var message = $('<div>', {'class' : 'confirmation hidden'}).html('Friend Request sent to '+friendName);
            $searchResult.prepend(message);
            message.fadeIn(400);
        });

        // todo: add this person to the list of pending friends.
        var pendingFriendList        = $('#pendingFriendList');
        var newPendingFriendListItem = $('<li>', {
                                        'data-friendid'   : friendId,
                                        'data-friendname' : friendName});
        var newPendingFriendInner    = $('<div>', {'class' : 'left'}).html(friendName);

        newPendingFriendListItem.append(newPendingFriendInner)
                                .hide();
        pendingFriendList.append(newPendingFriendListItem);
        newPendingFriendListItem.fadeIn(400);
    }

    // Handle the results of accepting a friend request
    function AcceptFriendRequestCB(data) 
    {
        // remove the request notification
        var friendId   = $(this).data('friendid');
        var listItem   = $(this).parents('li');
        var friendName = listItem.data('friendname');

        listItem.fadeOut(400, function() {
            var list = listItem.parents('ul');
            listItem.remove();
            if(list.find('li').length === 0)
            {
                list.siblings('h3').remove();
                list.remove();
            }

            // add the new friend to the friend list
            var noFriendsSpan = $('#noFriends');
            if(noFriendsSpan.length > 0)
            {
                noFriendsSpan.fadeOut(400);
            }

            var newListElement = $('<li style="display:none">'+friendName+'</li>');
            $('#friendList').append(newListElement);
            newListElement.fadeIn(400);
        });
    }

    // Handle the results of searching for a friend
    function SearchForFriendCB(data) 
    {
        console.log(data.result);
        $.each(data.result, function(id, name) {
            var friendDiv = $('<div class="friendResult" data-friendid="'+id+'">'+name+'</div>');
            var addButton = $('<button class="btn" data-friendid="'+id+'" data-friendname="'+name+'">Add</button>');
            friendDiv.append(addButton);
            $searchResultContainer.append(friendDiv);
        });
    }
});

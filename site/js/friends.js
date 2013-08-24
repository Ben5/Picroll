$(document).ready(function() {
    var minSearchLength = 2;

    // disable the button by default
    $('#searchSubmit').attr('disabled', 'disabled');

    var $searchResultContainer = $('#searchResult');
    var $friendRequestList = $('#friendRequestList');

    // click handler for all buttons that end up in the search result container.
    $searchResultContainer.delegate('div.friendResult button', 'click', function() {
        var friendId = $(this).data('friendid');
        var friendName = $(this).data('friendname');

        var url = '/picroll/json/friends/sendFriendRequest';
        $.get(url, 
            {
                friendId: friendId
            },
            function(data) {
                var $searchResult = $('#searchResult');

                $searchResult.find('div.friendResult[data-friendid="'+friendId+'"]').fadeOut(400, function() {
                        var message = $('<div class="confirmation">Friend Request sent to '+friendName+'</div>')
                        $searchResult.prepend(message);
                        message.fadeIn(400);
                    });
            },
            "json");
    });

    // click handler for all buttons that end up in the friend request list
    $friendRequestList.delegate('button', 'click', function() {
        var friendId   = $(this).data('friendid');

        var url = '/picroll/json/friends/acceptFriendRequest';
        $.get(url, 
            {
                friendId: friendId
            },
            function(data) {
                // remove the request notification
                var listItem = $('#friendRequestList').children('li[data-friendid="'+friendId+'"]');
                var friendName = listItem.data('friendname');

                listItem.fadeOut(400, function() {
                    var list = listItem.parents('ul');
                    listItem.remove();
                    if(list.find('li').length == 0)
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
            },
            "json");
    });
    
    // And show it when enough characters have been entered
    $('#friendSearch').on('keyup', function(event) {
        var inputLength = $(this).val().length;
        if(inputLength <= minSearchLength)
        {
            $('#searchSubmit').attr('disabled', 'disabled');
        }
        else
        {
            $('#searchSubmit').attr('disabled', false);
        }
    });

    // Search button handler
    $('#searchSubmit').on('click', function() {
        var searchVal = $('#friendSearch').val();
        if(searchVal.length <= minSearchLength)
        {
            console.log('enter more characters');
            return false;
        }

        $searchResultContainer.empty();
        
        var url = '/picroll/json/friends/searchForFriend';
        
        $.get(url, 
            {
                searchTerm: searchVal
            },
            function(data) {
                console.log(data.result);
                $.each(data.result, function(id, name) {
                    var friendDiv = $('<div class="friendResult" data-friendid="'+id+'">'+name+'</div>');
                    var addButton = $('<button class="btn" data-friendid="'+id+'" data-friendname="'+name+'">Add</button>');
                    friendDiv.append(addButton);
                    $searchResultContainer.append(friendDiv);
                });
            },
            "json");
    });
});

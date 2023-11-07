# CST8257-Project
Main repository for CST8257 Project final project: A social media network website for picture album management and sharing. 

## Requirements

1. This is the comprehensive project of this course. Compared with the other labs, the amount of workload evolved in this project is significant. You should reuse your work in lab 5 and lab 6 as much as possible. Your final deliverables must be at commercial-grade. To be specific: 
1. The website is secure. At minimum, it is protected from password stealing and SQL injection.

## Database

Use the following relational data model to create a database in mySQL Workbench:
![image](https://github.com/Camilo0717/CST8257-Project/assets/86133905/5538e7c0-c705-440a-8ace-bf07921af0f8)

## Website

### header.php and footer.php: 

All pages on this website have the following header. The header.php page should have the following menu items:

* Home – link to Index.php page
* My Friends – link to MyFriends.php page
* My Albums – link to MyAlbums.php page
* My Pictures – link to MyPictures.php page
* Upload Pictures – link to UploadPictures.php page
* Log In / Log Out – link to Login.php if the user is not logged in yet, or Logout.php page if the user has already logged in.

All pages on this website should have a common footer with the copyright information. 

### index.php

This page is the landing (default) page of this website. A mockup of this page is shown next:

![image](https://github.com/Camilo0717/CST8257-Project/assets/86133905/e66fe95e-2f0b-46ec-b19e-c33555e178e7)

### NewUser.php

The same as in lab 5, this page is for a user to sign up with the system. The same validation rules as in lab 5 apply as well. Passwords cannot be saved in the User table as plain text. They must be hashed. A mockup of this page is shown next:

![image](https://github.com/Camilo0717/CST8257-Project/assets/86133905/73041577-008c-4a55-9c1c-6a5ad290f9a8)

### Login.php

This page allows the signed-up users to log into the website with their student ID and password.
Except the Landing page (index.php), New User page (NewUser.php) and this page, all other pages of the website are protected, requiring users to be authenticated to access. If an unauthenticated user tries to access a protected page of the website, he/she will be redirected to this login page for authentication.
Once successfully authenticated, the user will be redirected back to the protected page he/she was attempting to access. A mockup of this page is shown next:

![image](https://github.com/Camilo0717/CST8257-Project/assets/86133905/8312da19-90ca-4213-8043-ae0a6b1ea6f1)

### AddAlbum.php

This page is used to create a new album for the user. 

On this page, the user specifies the title of the album, select the accessibility from the dropdown list and optionally the detailed description of the album.
The possible selections of dropdown list come from CST8257 database table Accessibility.
Currently there are two entries in the table:
* private – The album is only accessible by the user him/her self.
* shared – The album is accessible by the user and the user’s friend. 

![image](https://github.com/Camilo0717/CST8257-Project/assets/86133905/e0c011d6-9c6c-4e76-8bd5-a23965789feb)

### MyAlbums.php

This page lists user’s albums. For each album, it lists the following information about the album:
* Title – The title of the album
* Number of pictures – The number of pictures the album contains
* Accessibility – the album’s accessibility. It is shown as the selection of the dropdown list. The available selections in the dropdown list come from the database table Accessibility.
  
The user can change the of albums’ accessibility by selecting the desired accessibility from
the dropdown list for each album and click the Save Change button. 

![image](https://github.com/Camilo0717/CST8257-Project/assets/86133905/341a2e3d-ed67-47c7-8242-4eee9b2464c7)

The page also has following links:
* Create a New Album – link to AddAlbum.php page
* Album Titles – All album titles in the album list are links to MyPictures.php page once clicked, the user is brought to MyPictures.php page with the clicked album as the selected album in the dropdown list, see MyPictures.php section for details of MyPictures.php page.
* DELETE – a link button to delete the album. Once clicked, the system will prompt the
user to confirm that all pictures in the album will be delete with the album. 

### UploadPictures.php

The user uses this page to upload pictures into one of his/her albums. 

![image](https://github.com/Camilo0717/CST8257-Project/assets/86133905/8566033e-f28d-4831-aaeb-d7d2f81ee08b)

The user can upload single or multiple pictures at a time. Optionally, the user can specify title and description for the pictures to be uploaded. When uploading multiple pictures at a time, the specified title and description apply to all the pictures uploaded. 

### MyPictures.php

This page is for the user to browse and manage his/her pictures. The page contains the following elements:
* A dropdown list for the user to select an album to browse its pictures.
* A picture area showing the picture selected when the user clicks its thumbnail.
* A thumbnail bar displays all thumbnails of the pictures contained in the album. When a thumbnail is clicked the picture area displays the picture the thumbnail represents. The thumbnail of the picture currently in display should be highlighted with a blue border.
* A description and comment area showing the picture’s description (if exists) and
comments (if any). The comments are ordered chronically from the newest to the oldest.
* A text area for the user to leave a comment. The user can write a comment and click the
Add Comment button to leave a comment about the picture. 

![image](https://github.com/Camilo0717/CST8257-Project/assets/86133905/612e8f85-abff-45c5-89c2-bd2552ce2678)

### AddFriend.php

The user can use this page to send a friend request to another user by enter the other user’s
user ID and click the Send Friend Request button. 

![image](https://github.com/Camilo0717/CST8257-Project/assets/86133905/bf21c218-74de-478b-a225-6a1a21066194)

A friend request must follow the following rule:
* The entered user ID must exist.
* A user cannot send a friend request to her/himself.
* If A sends a friend request to B, while A has a friend request from B waiting for A to
accept, A and B become friends.
* If A and B are friends, a friend request between them will result in a message: “You
and A (or B) are already friends”. 

If the request passes the above rules, the user will get a confirmation message to confirm
that the friend request has sent to the specified user: 

![image](https://github.com/Camilo0717/CST8257-Project/assets/86133905/565d768f-67ce-4b5e-9ffd-8e0c9c6dbb1b)

### MyFriends.php

This page lists the user’s friends and friend requests. For each friend, it shows the number of
shared albums of that friend. The user can perform the following action for each friend.

* Click a friend’s name to view the friend’s pictures in the shared albums if that friend
has album(s) to share. 
* Select the checkbox and click Defriend Selected to remove the selected friend(s)
from the friend list.

![image](https://github.com/Camilo0717/CST8257-Project/assets/86133905/bf772bec-a5a8-4cf5-b887-f57c8438aaff)

For each friend request, the user can check the checkbox and click Accept Selected to
accept the friend request to become a friend of the requester. Or click Deny Selected to
decline the friend request. Once accepted or declined, the friend request will be removed
from the friend request list. 

The page also contains a link Add Friends to page AddFriend.php. When Defriend a friend or Deny a friend request, the user should be given a proper warning: 

![image](https://github.com/Camilo0717/CST8257-Project/assets/86133905/a9d93b66-fed9-46a6-9c2e-c6e8f93a0785)

### FriendPictures.php 

This page is the same as MyPicture.php except that the album dropdown list only has the
friend’s shared albums for selection. 

![Uploading image.png…]()


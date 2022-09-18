# mollie
Mollie WordPress Challenge

## Website URL https://mollie.nova-green.ru/

## FTP access to the hosting with the website
host: 91.219.194.3
user: mollie@mollie.nova-green.ru
password: a$RQxVj!YLI+


## WordPress admin dashboard access
https://mollie.nova-green.ru/wp-admin
username: mollie_admin
password: 4eQ&mS#E46

## Navigation in the code

You can find the plugin in wp-content/plugins/wp_fnugg_resort folder

1. in "assets" folder there are webpack generated styles and scripts
2. in inc/classes/ I created two classes "block" and "connector". In class-block.php I created a function for the new block. In class-connector.php I connected the block to Fnugg API, coded the search functionality and defined variables for the block's frontend.
3. In src/js/block.js I registered the block in Widgets section of the Gutenberg, set up search autocomplete and wp-admin block visual with tips.
4. In templates/block-template.php I created HTML markup for the new block's frontend and placed there the variables from the API.

## Frontend functionality
While editing posts or pages in wp-admin you can choose a Gutenberg block for Resorts under the WIDGETS section
![1](https://user-images.githubusercontent.com/44933254/190919631-b6eaac1b-2626-4cc4-bd15-20504ba3a2d1.png)

When the block is added, to choose the needed resort from the Fnugg API please or write "/" and start typing the name of the resort 
![3](https://user-images.githubusercontent.com/44933254/190919904-087f8721-a8b4-485d-8dd4-d31ef01addb5.png)

or write the first word of the Resort name correctly.
![5](https://user-images.githubusercontent.com/44933254/190919951-3de9b62a-c5ec-4700-a6c2-28ab1b8d43ee.png)

if the admin will write the first word of the name incorrectly, on the frontend users will see
![image](https://user-images.githubusercontent.com/44933254/190920005-7379129b-1102-4af5-af70-2aa0972f4791.png)




If there is a resort chosen in the block, users will see the block on the frontend that looks like this
![4](https://user-images.githubusercontent.com/44933254/190920019-2593db5e-de9c-45a9-b626-f91739a0cc35.png)

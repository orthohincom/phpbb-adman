This is an Advertise Management Plugin. It helps you dynnamically create addvertisement blocks in your forum and add code in those blocks.

## Installation
Upload the `adman` directory to `/ext/orthohin/` directory.
Activate it from ACP > Customize

## Creat a New add block
In ACP > Extensions > ADVERTISE MANAGEMENT
Click `Add new Advertisement`

In the name fiels, Enter the exact name of the phpBB template event where you want to put the ad-block.
You can see a list of events here: https://wiki.phpbb.com/Event_List#Template_Events
or use this ext https://www.phpbb.com/community/viewtopic.php?f=456&t=2283446#p14056576
### _Important_
you need to create an HTML file manually here:
`/ext/orthohin/adman/styles/prosilver/template/event/`
The file name should the `EVENT_NAME.html`
and the file  should contain the following line in it:

    {EVENT_NAME}
    
You need replace EVENT_NAME with the event name you used.

## To Do
- [ ] Create the HTML file automatically as new events are added.

<a style="color: #eee; 
   font-family: Verdana; 
   font-size: 32px;
   text-decoration: none;
   line-height: 20px; 
   position: fixed; 
   top: 0; 
   left: 0; 
   background: #444; 
   display: block; 
   padding: 10px; 
   border-radius: 0 0 10px 0;
   z-index: 100000;" 
   href="
   {if $originalId}
       {* draft *}
       {@module=cmsAdmin&controller=category&action=edit&id={$categoryId}&originalId={$originalId}&uploaderId={$categoryId}@}
   {else}
       {* nie draft *}
       {@module=cmsAdmin&controller=category&action=edit&id={$categoryId}&force=1@}
   {/if}
   ">
    &#9998;
</a>
<div class="clearfix">
    <h3 class="content-heading">{{ t('Share This') }}</h3>
    @include('master/share')
</div>
<div class="clearfix">
    <h3 class="content-heading">{{ t('Products') }}</h3>

     <ul>
        <?php

        $curDepth = 0;
        $counter = 0;
        foreach (productCategories() as $category){

            if ($category->depth == $curDepth){
                if ($counter > 0) echo "</li>";
            }
            elseif ($category->depth > $curDepth){
                echo "<ul>";
                $curDepth = $category->depth;
            }
            elseif ($category->depth < $curDepth){
                echo str_repeat("</li></ul>", $curDepth - $category->depth), "</li>";
                $curDepth = $category->depth;
            }

            ?>
            <li id='category_{{ $category->id }}' data-id='{{ $category->id }}'>
            <a href="{{ $category->getLink() }}">{{ $category->name }}</a>
            <?php

            $counter++;
        }

        echo str_repeat("</li></ol>", $curDepth), "</li>";

        ?>
    </ul>

</div>
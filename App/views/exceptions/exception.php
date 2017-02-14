<strong>
    Message
</strong> <br>
<p><?php echo $exception->getMessage();?></p>

<strong>
    Trace
</strong> <br>
<pre>
    <?php print_r($exception->getTrace());?>
</pre>

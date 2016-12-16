<?php if (isset($result)  && $result == 'success') { ?>
    <h3><?php echo $message; ?></h3>
    <a href="/">На страницу магазина</a>
<?php } else { ?>
<div class="wide form">
    <?php if (isset($needEmail)) { ?>
    <form action="<?= Yii::app()->createUrl('site/checkout/' . Yii::app()->user->getState('productId')); ?>" method="post">
        <div class="raw">
            <h3>Для совершения оплаты введите Ваш E-mail</h3>
        </div>
        <div class="raw">
            <input type="text" value="<?= !empty($email) ? $email : '' ?>" name="userEmail" />
            <?php if (isset($result) && $result == 'invalid-email'): ?>
            <br />
            <small class="error"><?php echo $message; ?></small>
            <?php endif; ?>
        </div>
        <div class="raw">
            <input type="submit" value="Подтвердить" />
        </div>
    </form>
    <?php } ?>

    <?php if (!isset($result) && isset($stripe)) { ?>
    <form action="<?= Yii::app()->createUrl('site/checkout/' . Yii::app()->user->getState('productId')); ?>" method="post">
        <div class="raw">
        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="<?php echo $stripe['publishable_key']; ?>"
                data-description="<?php echo $stripe['name']; ?>"
                data-amount="<?php echo $stripe['price']; ?>"
                data-locale="auto"></script>
        </div>
    </form>
    <?php } ?>
</div>
<?php } ?>
<div class="page-header">
    <h2><?= t('Save Chat ID') ?></h2>
</div>

<?php if (empty($chat_id)): ?>
    <div class="confirm">
    <p class="alert alert-info"><?= t('Message %s not found!', $private_message) ?></p>
    <p class="info"><?= t('Please send message %s ', $private_message) ?><br/>
    to <a href="<?= $bot_url ?>" target="_blank" rel="noreferrer"><?= $bot_url ?></a></p>
    <br/>
    <p>If you wish connect the bot to chat room, please ensure that the bot have admin rights!</p>
    </div>
<?php else: ?>
    <div class="confirm">
    <p class="alert alert-info">
     <?= t('Save chat id="%s" from "%s"?', $this->text->e($chat_id), $this->text->e($user_name)) ?>
    </p>

    <?= $this->modal->confirmButtons(
          'TelegramController',
          'save_user_chat_id',
          array('plugin' => 'Telegram', 'chat_id' => $chat_id)
        ) ?>
    </div>
<?php endif ?>

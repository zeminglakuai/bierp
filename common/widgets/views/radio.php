<div class="form-group">
    <label class="col-sm-2 control-label"><?= $label_name ?></label>
    <div class="col-sm-9">
        <div class="row" style="margin-left: 0px;">
            <?php if ($init_value) {
                foreach ($init_value as $key => $vv) {
            ?>
                    <div class="col-sm-1">
                        <div class="radio i-checks" style="padding-left: 15px;">
                            <label><input type="radio" style="margin-left: -17px;margin-top: 0px;" value="<?= $vv['value'] ?>" name="<?= $name ?>" <?= $value == $vv['value'] ? 'checked="checked"' : '' ?>> <i></i><?= $vv['label_name'] ?></label>
                        </div>
                    </div>
            <?php
                }
            } ?>
        </div>
    </div>
</div>
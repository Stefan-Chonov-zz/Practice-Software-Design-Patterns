<?php require_once '../app/views/partial/header.html'; ?>

    <div class="container">

        <div class="form-container">
            <div class="formMessage <?php echo (isset($data['form']['hasError']) && $data['form']['hasError']) ? 'error' : 'sucessfull'; ?>" <?php echo (!empty($data['form']['message'])) ? '' : 'style="display: none;"'; ?>><?php echo $data['form']['message']; ?></div>
            <form id="userForm" action="/mvc/user/create" method="POST">
                <div class="row">
                    <div class="cell left">
                        <label for="firstName">First name</label>
                    </div>
                    <div class="cell right">
                        <input type="text" name="firstName" id="firstName" value="<?php echo !empty($data['form']['data']['firstName']) ? $data['form']['data']['firstName'] : ''; ?>" />
                    </div>
                    <div class="errorMessage" <?php echo (isset($data['form']['hasError']) && $data['form']['hasError'] && !empty($data['form']['errors']['firstName'])) ? 'style="display: initial;"' : 'style="display: none;"\''; ?>><?php echo !empty($data['form']['errors']['firstName']) ? $data['form']['errors']['firstName']['message'] : ''; ?></div>
                </div>

                <div class="row">
                    <div class="cell left">
                        <label for="surName">Surname</label>
                    </div>
                    <div class="cell right">
                        <input type="text" name="surName" id="surName" value="<?php echo !empty($data['form']['data']['surName']) ? $data['form']['data']['surName'] : ''; ?>" />
                    </div>
                    <div class="errorMessage" <?php echo (isset($data['form']['hasError']) && $data['form']['hasError'] && !empty($data['form']['errors']['surName'])) ? 'style="display: initial;"' : 'style="display: none;"\''; ?>><?php echo !empty($data['form']['errors']['surName']) ? $data['form']['errors']['surName']['message'] : ''; ?></div>
                </div>

                <div class="row">
                    <div class="cell left">
                        <label for="address">Address</label>
                    </div>
                    <div class="cell right">
                        <input type="text" name="address" id="address" value="<?php echo !empty($data['form']['data']['address']) ? $data['form']['data']['address'] : ''; ?>" />
                    </div>
                    <div class="errorMessage" <?php echo (isset($data['form']['hasError']) && $data['form']['hasError'] && !empty($data['form']['errors']['address'])) ? 'style="display: initial;"' : 'style="display: none;"'; ?>><?php echo !empty($data['form']['errors']['address']) ? $data['form']['errors']['address']['message'] : ''; ?></div>
                </div>

                <div class="row">
                    <div class="cell left">
                        <label for="country">Country</label>
                    </div>
                    <div class="cell right">
                        <select name="countryId">
                            <option value="" <?php echo empty($data['form']['data']['countryId']) ? 'selected disabled hidden' : ''; ?>>Choose country</option>
                            <?php
                            foreach ($data['countries'] as $country) {
                                echo '<option value="' . $country['id'] . '" ' . ((!empty($data['form']['data']['countryId']) && $data['form']['data']['countryId'] == $country['id']) ? 'selected' : '') . '>' . $country['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="errorMessage" <?php echo (isset($data['form']['hasError']) && $data['form']['hasError'] && !empty($data['form']['errors']['countryId'])) ? 'style="display: initial;"' : 'style="display: none;"\''; ?>><?php echo !empty($data['form']['errors']['countryId']) ? $data['form']['errors']['countryId']['message'] : ''; ?></div>
                </div>

                <div class="row">
                    <div class="cell left">
                        <label for="postcode">Postcode</label>
                    </div>
                    <div class="cell right">
                        <input type="text" name="postcode" id="postcode" value="<?php echo !empty($data['form']['data']['postcode']) ? $data['form']['data']['postcode'] : ''; ?>" />
                    </div>
                    <div class="errorMessage" <?php echo (isset($data['form']['hasError']) && $data['form']['hasError'] && !empty($data['form']['errors']['postcode'])) ? 'style="display: initial;"' : 'style="display: none;"\''; ?>><?php echo !empty($data['form']['errors']['postcode']) ? $data['form']['errors']['postcode']['message'] : ''; ?></div>
                </div>

                <div class="row">
                    <div class="cell left">
                        <label for="phone">Phone</label>
                    </div>
                    <div class="cell right">
                        <input type="tel" name="phone" id="phone" value="<?php echo !empty($data['form']['data']['phone']) ? $data['form']['data']['phone'] : ''; ?>" />
                    </div>
                    <div class="errorMessage" <?php echo (isset($data['form']['hasError']) && $data['form']['hasError'] && !empty($data['form']['errors']['phone'])) ? 'style="display: initial;"' : 'style="display: none;"\''; ?>><?php echo !empty($data['form']['errors']['phone']) ? $data['form']['errors']['phone']['message'] : ''; ?></div>
                </div>

                <div class="row">
                    <div class="cell left">
                        <label for="email">Email</label>
                    </div>
                    <div class="cell right">
                        <input type="email" name="email" id="email" value="<?php echo !empty($data['form']['data']['email']) ? $data['form']['data']['email'] : ''; ?>" />
                    </div>
                    <div class="errorMessage" <?php echo (isset($data['form']['hasError']) && $data['form']['hasError'] && !empty($data['form']['errors']['email'])) ? 'style="display: initial;"' : 'style="display: none;"\''; ?>><?php echo !empty($data['form']['errors']['email']) ? $data['form']['errors']['email']['message'] : ''; ?></div>
                </div>

                <div class="row" style="width: 100%;">
                    <input type="submit" value="Submit" id="submit">
                    <!--                <input type="button" id="clearButton" onclick="$('#userForm')[0].reset();" value="CLEAR">-->
                    <button type="button" id="clearButton">CLEAR</button>
                </div>
            </form>
        </div>

    </div>

    <script type="text/javascript" src="../public/js/app.js"></script>

<?php require_once '../app/views/partial/footer.html'; ?>
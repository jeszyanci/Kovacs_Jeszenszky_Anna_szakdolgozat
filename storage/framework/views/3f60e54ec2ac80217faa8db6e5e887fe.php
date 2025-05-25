<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<section>
    <div class="menu" style="display: inline-flex;">
        <div id="backBtn" onclick="window.location='<?php echo e(route('mainPage')); ?>'" class="button box">Vissza</div>
    </div>
</section>

<section>
    <div settingsHolder class="box light" style="display:flex; flex-direction: column; text-align: center">
        <div class="boxTitle">Felhasználói adatok</div>
        <div class="personalData" style="width:100%; padding: 10px;">

            <div>
                <div style="display: inline-block; width: 150px">Név: </div>
                <input param="name" type="text">            
            </div>
            
            <div>
                <div style="display: inline-block; width: 150px">Becenév: </div>
                <input param="nickname" type="text">            
            </div>
    
            <div>
                <div style="display: inline-block; width: 150px">E-mail: </div>
                <input param="email" type="text">
            </div>
            <div>
                <div style="display: inline-block; width: 150px">Új jelszó: </div>
                <input param="password" type="password">
            </div>
            <div>
                <div style="display: inline-block; width: 150px">Jelszó újra: </div>
                <input param="password" type="password">
            </div>

            <div saveChanges='self' class="button" style="margin-left: auto; margin-right: auto">Változtatások mentése</div>
        </div>
    </div>

    <div class="adminSettings box light" style="display:none; margin-top: 20px;">
        <div class="adminSettings" style="display: none; text-align: center">
            <div class="boxTitle">Admin beállítások</div>

            <div class="button" AddNewUserButton style="margin-left: auto; margin-right: auto">Új felhasználó</div>

            <div class="boxTitle" style="width:80%; margin-top:10px; border-radius:10px; margin-left: auto; margin-right: auto;">Felhasználói adatok módosítása</div>
            <select userSelector class="box light"></select>

            <div class="userDetails">
                <div>
                    <div style="display: inline-block; width: 150px">Név: </div>
                    <input param="name" type="text">            
                </div>

                <div>
                    <div style="display: inline-block; width: 150px">Becenév: </div>
                    <input param="nickname" type="text">            
                </div>
        
                <div>
                    <div style="display: inline-block; width: 150px">E-mail: </div>
                    <input param="email" type="text">
                </div>
                <div>
                    <div style="display: inline-block; width: 150px">Új jelszó: </div>
                    <input param="password" type="text">
                </div>
            </div>


            <div saveChanges='selectedUser' class="button" style="margin-left: auto; margin-right: auto">Változtatások mentése</div>
            <div deleteUser class="button" style="color:red; margin-left: auto; margin-right: auto">Felhasználó törlése</div>
        </div>
    </div>
</section>

<script>
    const userdataTypes = ['name', 'nickname', 'email', 'password'];

    window.onload = async () => {
        // all user data
        let allUserData = "";

        let userAjax = new Promise ((resolve, reject) => {
            $.ajax({
                type: 'GET',
                url:  '<?php echo e(url("/profile/data/getAllUser")); ?>',
                success: (response) => {
                    allUserData = JSON.parse(response);
                    resolve();
                },
                error: (error) => {
                    reject(error);
                }
            });
        });

        await userAjax;
        if (userAjax) {
            let selectElem = $('.adminSettings [userSelector]');

            allUserData.forEach( row => {
                $(`<option value="${row.id}">${row.name} (${row.nickname})</option>`).appendTo(selectElem);
            });
        }

        // self data
        let userData = "";

        let selfAjax = new Promise ((resolve, reject) => {
            $.ajax({
                type: 'GET',
                url:  '<?php echo e(url("/profile/data")); ?>',
                success: (response) => {
                    userData = JSON.parse(response).userData;
                    resolve();
                },
                error: (error) => {
                    reject(error);
                }
            });
        });

        await selfAjax;
        if (selfAjax) {
            userdataTypes.forEach( e => {
                $(`.personalData input[param=${e}]`).val(userData[e]);

                if (userData.role == 1) {
                    $('.adminSettings').css('display', 'block');
                    $(`.adminSettings input[param=${e}]`).val(allUserData[0][e]);
                }
            })
        }
        
        // user selector
        $('.adminSettings [userSelector]').on('change', e => {
            let userID = $(e.target).find('option:selected').val();
            let selectedUser = allUserData.find( user => user.id == userID);

            userdataTypes.forEach( e => {
                $(`.adminSettings input[param=${e}]`).val(selectedUser[e]);
            })
        });
    
        // save self data
        $('[saveChanges="self"]').click( e => {
            let newData = {
                name:     userData.name,
                nickname: userData.nickname,
                email:    userData.email,
                password: userData.password
            }
            
            userdataTypes.forEach( e => {
                let value = $(`.personalData input[param=${e}]`).val();
                newData[e] = value.length > 0 ? value : newData[e];
            })

            $.ajax({
                type: 'GET',
                url:  '<?php echo e(route("saveUserData")); ?>',
                data: {newData: newData}
            });
        });
        
        // save selected user data
        $("[saveChanges='selectedUser']").click( e => {
            let userID = $(e.target).find('option:selected').val();
            let selectedUser = allUserData.find( user => user.id == userID);

            let newData = {
                userid: userID
            }

            userdataTypes.forEach( e => {
                let value = $(`.adminSettings input[param=${e}]`).val();
                newData[e] = value.length > 0 ? value : selectedUser[e];
            })

            $.ajax({
                type: 'GET',
                url:  '<?php echo e(route("saveUserData")); ?>',
                data: {
                    newData: newData
                },
                success: (response) => {
                    
                }
            });
        });

        // delete user
        $("[deleteUser]").click( e => {
            let userID = $("[userselector]").find('option:selected').val();

            $.ajax({
                type: 'GET',
                url:  '<?php echo e(route("deleteUser")); ?>',
                data: {
                    userID: userID
                }
            });
        });

        // add new user
        let newUserPopup = $(`
            <div class="popup" style="display: none;">
                <div class="popupContent" style="display: flex; flex-direction: column;">
                    <div>
                        <span>Name: </span>
                        <input param="name" type="text">            
                    </div>
                    
                    <div>
                        <span>Nickname: </span>
                        <input param="nickname" type="text">            
                    </div>
            
                    <div>
                        <span>Email: </span>
                        <input param="email" type="text">
                    </div>
                    
                    <div>
                        <span>Password: </span>
                        <input param="password" type="text">
                    </div>
                    <div>
                        <span>Role: </span>
                        <select param="role">
                            <option value="1">Admin</option>
                            <option value="2">User</option>
                        </select>
                    </div>
                    <div saveNewUser class="button">Mentés</div>
                </div>
            </div>
        `).appendTo('body');

        newUserPopup.find('[saveNewUser]').click( e => {
            let newUser = {
                name:     newUserPopup.find(`[param="name"]`).val(),
                nickname: newUserPopup.find(`[param="nickname"]`).val(),
                email:    newUserPopup.find(`[param="email"]`).val(),
                password: newUserPopup.find(`[param="password"]`).val(),
                role:     newUserPopup.find(`[param="role"]`).val()
            }

            $.ajax({
                type: 'GET',
                url:  '<?php echo e(route("addNewUser")); ?>',
                data: {
                    newUser: newUser
                },
                success: (response) => {
                    newUserPopup.hide();
                }
            });
        });

        $('[AddNewUserButton]').click( e => {
            newUserPopup.show();
        });
    }


</script><?php /**PATH C:\laragon\www\KJ_Anna_szakdolgozat\resources\views/profile.blade.php ENDPATH**/ ?>
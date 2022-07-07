import React, {useEffect} from 'react';
import {Button, Col} from "react-bootstrap";
import i18n from "../../../i18n";
import i18next from "../../../i18n";
import {LANGUAGE_ENGLISH, LANGUAGES} from "../../../constants/languages";
import {FilePond, registerPlugin} from "react-filepond";
import Image from "react-bootstrap/Image";
import FilePondPluginImageExifOrientation from "filepond-plugin-image-exif-orientation";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import moment from "moment";
import {authFetch,} from "../../../providers/authProvider";
import history from "../../../history";
import {PAGE_HOME} from "../../../constants/routes";
import {URL_API_PROFILE_CONNECT_SOCIAL_NETWORK} from "../../../constants/urls";
import {toast} from "react-toastify";
import {fetchUser} from "../../../store/action-creators/user";
import {useTypedSelector} from "../../../hooks/useTypedSelector";
import {useDispatch} from "react-redux";
import {CheckBox, DateBox, SelectBox, TextBox} from "devextreme-react";

registerPlugin(FilePondPluginImageExifOrientation, FilePondPluginImagePreview);

const ProfileForm = () => {
    const {user} = useTypedSelector(state => state.user);
    const dispatch = useDispatch();
    const [facebookConnected, setFacebookConnected] = React.useState(false);
    const [files] = React.useState([]);
    const [countries, setCountries] = React.useState([]);
    const [regions, setRegions] = React.useState([]);
    const [region, setRegion] = React.useState({});
    const [name, setName] = React.useState('');
    const [surname, setSurname] = React.useState('');
    const [squad, setSquad] = React.useState('');
    const [birthday, setBirthday] = React.useState(new Date());
    const [country, setCountry] = React.useState('');
    const [language, setLanguage] = React.useState('us');
    const [showPhotoAtStream, setShowPhotoAtStream] = React.useState(false);
    const [showPhotoAtWeb, setShowPhotoAtWeb] = React.useState(false);
    const [showSquadLogoAtStream, setShowSquadLogoAtStream] = React.useState(false);
    const [showSquadLogoAtWeb, setShowSquadLogoAtWeb] = React.useState(false);
    const [showSquadAtStream, setShowSquadAtStream] = React.useState(false);
    const [showSquadAtWeb, setShowSquadAtWeb] = React.useState(false);
    const [showBirthdayAtWeb, setShowBirthdayAtWeb] = React.useState(false);
    const [showBirthdayAtStream, setShowBirthdayAtStream] = React.useState(false);
    const [photo, setPhoto] = React.useState('');
    const [squadLogo, setSquadLogo] = React.useState('');
    const [youtubeChannelUrl, setYoutubeChannelUrl] = React.useState('');
    const [email, setEmail] = React.useState('');
    const [twitchChannelUrl, setTwitchChannelUrl] = React.useState('');
    const [vkProfileUrl, setVkProfileUrl] = React.useState('');
    const [facebookProfileUrl, setFacebookProfileUrl] = React.useState('');
    const [instagramProfileUrl, setInstagramProfileUrl] = React.useState('');
    const [token, setToken] = React.useState({});

    useEffect(() => {
        if (user) {
            setToken(JSON.parse(localStorage.getItem('token') ?? '{}'));
            fetch(process.env.REACT_APP_API_HOST + `/api/open/regions/list`)
                .then(r => r.json())
                .then(_regions => {
                    setRegions(_regions);
                    setRegion(_regions.find((r) => r.id === user.region?.id))
                });
            setEmail(user.email ?? '')
            setPhoto(user.photo ?? '')
            setSquadLogo(user.squadLogo ?? '')
            setLanguage(user.language || LANGUAGE_ENGLISH);
            setName(user.name ?? '');
            setSurname(user.surname ?? '');
            setSquad(user.squad ?? '');
            setBirthday(new Date(user.birthday));
            setYoutubeChannelUrl(user.youtubeChannelUrl ?? '');
            setTwitchChannelUrl(user.twitchChannelUrl ?? '');
            setVkProfileUrl(user.vkProfileUrl ?? '');
            setShowPhotoAtStream(user.showPhotoAtStream || false);
            setShowPhotoAtWeb(user.showPhotoAtWeb || false);
            setShowBirthdayAtStream(user.showBirthdayAtStream || false);
            setShowBirthdayAtWeb(user.showBirthdayAtWeb || false);
            setShowSquadAtWeb(user.showSquadAtWeb || false);
            setShowSquadAtStream(user.showSquadAtStream || false);
            setShowSquadLogoAtWeb(user.showSquadLogoAtWeb || false);
            setShowSquadLogoAtStream(user.showSquadLogoAtStream || false);

            handleLanguageChange({target: {value: user.language}} ?? '');
        }
    }, [user]);

    const handleLanguageChange = (e) => {
        if (!e.value) return
        setLanguage(e.value);
    };

    const handleRegionChange = (e) => {
        const reg = regions.find(r => r.id === parseInt(e.value));
        setRegion(reg);
    };
    const redirectToHome = () => {
        history.push(PAGE_HOME);
    };

    const responseFacebook = (response) => {
        saveSocialNetworkConnection(response.id);
    }

    const saveSocialNetworkConnection = (facebookId) => {
        if (!facebookId) {
            return;
        }
        authFetch(URL_API_PROFILE_CONNECT_SOCIAL_NETWORK, {
            method: 'PUT',
            body: JSON.stringify({facebookId})
        })
            .then(r => r.json())
            .then(data => {
                const {status} = data;
                if (status === 0) {
                    toast.success(i18next.t('message.facebook_acc_connected'), {
                        position: "top-right",
                        autoClose: 5000,
                    });
                    setFacebookConnected(true);
                }
            })
            .catch(err => {
                // console.log(err);
            })
        ;
    };

    const onPhotoProcessed = () => {
        setPhoto('');
        toast.success(i18next.t('message.photo_uploaded'), {
            position: "top-right",
            autoClose: 5000,
        });
    }

    const onSquadLogoProcessed = () => {
        setSquadLogo('');
        toast.success(i18next.t('message.logo_uploaded'), {
            position: "top-right",
            autoClose: 5000,
        });
    }

    const savePilotProfile = async () => {
        const data = {
            name: name?.trim(),
            region: region?.id,
            surname: surname?.trim(),
            country: country?.toLowerCase()?.trim(),
            language: language?.toLowerCase(),
            birthday: moment(birthday).format('yyyy-MM-DD'),
            squad: squad?.trim(),
            youtubeChannelUrl: youtubeChannelUrl?.trim(),
            twitchChannelUrl: twitchChannelUrl?.trim(),
            vkProfileUrl: vkProfileUrl?.trim(),
            showPhotoAtWeb,
            showPhotoAtStream,
            showSquadLogoAtStream,
            showSquadLogoAtWeb,
            showBirthdayAtStream,
            showBirthdayAtWeb,
            showSquadAtStream,
            showSquadAtWeb
        };
        const req = await authFetch(process.env.REACT_APP_API_HOST + `/api/${i18n.language}/profile/edit`, {
            method: "PUT",
            body: JSON.stringify(data),
        });
        const res = await req.json();
        if (res.status === 1) {
            toast.error(i18next.t(res.message), {
                position: "top-right",
                autoClose: 5000,
            });
        } else {
            dispatch(fetchUser());
            toast.success(i18next.t(res.message), {
                position: "top-right",
                autoClose: 5000,
            });
        }
    };

    const handleNameChange = (e) => {
        setName(e.value);
    };
    const handleEmailChange = (e) => {
        setEmail(e.value);
    };
    const handleSurnameChange = (e) => {
        setSurname(e.value);
    };
    const handleCountryChange = (e) => {
        if (!e.value) return;
        const _countr = countries.find(_country => _country.cca2.toLowerCase() === e.value.toLowerCase());
        if (_countr) setCountry(_countr.cca2);
    };

    const renderCountryItem = (data) => {
        return <div className="custom-item">
            <span className={`flag-icon flag-icon-${data.cca2.toLowerCase()}`}/>
            <span>{data.name.common}</span>
        </div>;
    };

    const handleSquadChange = (e) => {
        setSquad(e.value);
    };
    const handleBirthdayChange = (e) => {
        setBirthday(new Date(e.value));
    };
    const handleYoutubeChange = (e) => {
        setYoutubeChannelUrl(e.value);
    };
    const handleTwitchChange = (e) => {
        setTwitchChannelUrl(e.value);
    };
    const handleVkChange = (e) => {
        setVkProfileUrl(e.value);
    };
    const handleInstagramChange = (e) => {
        setInstagramProfileUrl(e.value);
    };
    const handleFacebookChange = (e) => {
        setFacebookProfileUrl(e.value);
    };

    function handleShowBirthdayAtWeb(e) {
        setShowBirthdayAtWeb(e.value);
    }

    function handleShowBirthdayAtStream(e) {
        setShowBirthdayAtStream(e.value);
    }

    function handleShowSquadAtWeb(e) {
        setShowSquadAtWeb(e.value);
    }

    function handleShowSquadAtStream(e) {
        setShowSquadAtStream(e.value)
    }

    function handleShowPhotoAtStream(e) {
        setShowPhotoAtStream(e.value);
    }

    function handleShowSquadLogoAtWeb(e) {
        setShowSquadLogoAtWeb(e.value)
    }

    function handleShowSquadLogoAtStream(e) {
        setShowSquadLogoAtStream(e.value);
    }

    useEffect(() => {
        fetch('https://restcountries.com/v3.1/all')
            .then(r => r.json())
            .then(_countries => {
                setCountries(_countries);
                setCountry(_countries.find((c) => c.cca2.toLowerCase() === user.country)?.cca2?.toLowerCase())
            })
    }, []);

    function removeImage(event) {
        let isAccepted = window.confirm(i18n.t('message.confirm'));
        if (!isAccepted) {
            return;
        }
        const type = event.target.id;
        authFetch(process.env.REACT_APP_API_HOST + `/api/${i18n.language}/profile/remove-image/${type}`, {
            method: "POST",
        })
            .then(r => r.json())
            .then(response => {
                if (response.status === 0) {
                    document.getElementById(type).remove();
                    document.getElementById(`${type}-image`).remove();
                    toast.success(i18next.t('message.file_delete_success'), {
                        position: "top-right",
                        autoClose: 5000,
                    });
                } else {
                    toast.error(i18next.t('message.file_delete_fail'), {
                        position: "top-right",
                        autoClose: 5000,
                    });
                }
            })
            .catch(e => {
                toast.error(i18next.t('message.file_delete_fail'), {
                    position: "top-right",
                    autoClose: 5000,
                });
            })
        ;
    }

    function handleShowPhotoAtWeb(e) {
        setShowPhotoAtWeb(e.value);
    }

    return (
        <div className="dx-fieldset">
            <div className="dx-field">
                <div className="dx-field-label">{i18n.t('label.email')}</div>
                <div className="dx-field-value">
                    <TextBox type="email" defaultValue={email} name="ucid_profile[email]"
                             placeholder={i18n.t('label.email')}
                             readOnly
                             onValueChanged={handleEmailChange}/>
                </div>
            </div>
            <div className="dx-field">
                <div className="dx-field-label">{i18n.t('label.name')}</div>
                <div className="dx-field-value">
                    <TextBox type="text" value={name} name="ucid_profile[name]"
                             placeholder={i18n.t('placeholder.type_name')}
                             onValueChanged={handleNameChange}/>
                </div>
            </div>
            <div className="dx-field">
                <div className="dx-field-label">{i18n.t('label.surname')}</div>
                <div className="dx-field-value">
                    <TextBox type="text" value={surname} name="ucid_profile[surname]"
                             placeholder={i18n.t('placeholder.type_surname')}
                             onValueChanged={handleSurnameChange}/>
                </div>
            </div>
            <div className="dx-field">
                <div className="dx-field-label">{i18n.t('label.birthday')}</div>
                <div className="dx-field-value">
                    <DateBox value={birthday} onValueChanged={handleBirthdayChange}
                             pickerType="rollers"/>
                    <CheckBox className="m-2"
                              onValueChanged={handleShowBirthdayAtWeb}
                              value={showBirthdayAtWeb}
                              text={i18n.t('label.show_at_web')}
                    />
                    <CheckBox className="m-2"
                              onValueChanged={handleShowBirthdayAtStream}
                              value={showBirthdayAtStream}
                              text={i18n.t('label.show_at_stream')}
                    />
                </div>
            </div>
            <div className="dx-field">
                <div className="dx-field-label">{i18n.t('label.region')}</div>
                <div className="dx-field-value">
                    <SelectBox items={regions}
                               valueExpr="id"
                               displayExpr={i18n.language === LANGUAGE_ENGLISH ? 'titleEn' : 'title'}
                               defaultValue={user?.region?.id || regions[0].id}
                               onValueChanged={handleRegionChange}
                               placeholder={i18n.t('placeholder.choose_region')}
                    />
                </div>
            </div>
            <div className="dx-field">
                <div className="dx-field-label">{i18n.t('label.language')}</div>
                <div className="dx-field-value">
                    <SelectBox items={LANGUAGES}
                               valueExpr="code"
                               displayExpr="title"
                               value={language}
                               onValueChanged={handleLanguageChange}
                               placeholder={i18n.t('placeholder.select_language')}
                               showClearButton={true}/>
                </div>
            </div>
            <div className="dx-field">
                <div className="dx-field-label">{i18n.t('label.country')}</div>
                <div className="dx-field-value">
                    <SelectBox dataSource={countries}
                               valueExpr="cca2"
                               displayExpr="name.common"
                               value={country}
                               onValueChanged={handleCountryChange}
                               placeholder={i18n.t('placeholder.select_country')}

                               searchEnabled
                               searchMode="contains"
                               searchExpr="name.common"
                               showClearButton={true}
                               itemRender={renderCountryItem}
                    />
                </div>
            </div>
            <div className="dx-field">
                <div className="dx-field-label">{i18n.t('label.squad')}</div>
                <div className="dx-field-value">
                    <TextBox type="text" value={squad}
                             name="ucid_profile[squad]"
                             placeholder={i18n.t('placeholder.type_squad')}
                             onValueChanged={handleSquadChange}/>
                    <CheckBox
                        value={showSquadAtWeb} className="m-2"
                        onValueChanged={handleShowSquadAtWeb}
                        text={i18n.t('label.show_at_web')}
                    />
                    <CheckBox
                        value={showSquadAtStream} className="m-2"
                        onValueChanged={handleShowSquadAtStream}
                        text={i18n.t('label.show_at_stream')}
                    />
                </div>
            </div>
            <div className="dx-field">
                <div className={'dx-field-label'}>{i18n.t('label.youtube')}</div>
                <div className="dx-field-value">
                    <TextBox type="text"
                             value={youtubeChannelUrl}
                             name="ucid_profile[youtubeChannelUrl]"
                             placeholder="Youtube"
                             onValueChanged={handleYoutubeChange}/>
                </div>
            </div>
            <div className="dx-field">
                <div className={'dx-field-label'}>{i18n.t('label.twitch')}</div>
                <div className="dx-field-value">
                    <TextBox type="text"
                             value={twitchChannelUrl}
                             name="ucid_profile[twitchChannelUrl]"
                             placeholder="Twitch"
                             onValueChanged={handleTwitchChange}/>
                </div>
            </div>
            <div className="dx-field">
                <div className={'dx-field-label'}>{i18n.t('label.vk')}</div>
                <div className="dx-field-value">
                    <TextBox type="text"
                             value={vkProfileUrl}
                             name="ucid_profile[vkProfileUrl]"
                             placeholder="VKontakte"
                             onValueChanged={handleVkChange}/>
                </div>
            </div>
            <div className="dx-field">
                <div className={'dx-field-label'}>{i18n.t('label.facebook')}</div>
                <div className="dx-field-value">
                    <TextBox type="text"
                             value={facebookProfileUrl}
                             name="ucid_profile[facebookProfileUrl]"
                             placeholder="Facebook"
                             onValueChanged={handleFacebookChange}/>
                </div>
            </div>
            <div className="dx-field">
                <div className={'dx-field-label'}>{i18n.t('label.instagram')}</div>
                <div className="dx-field-value">
                    <TextBox type="text"
                             value={instagramProfileUrl}
                             name="ucid_profile[instagramProfileUrl]"
                             placeholder="Instagram"
                             onValueChanged={handleInstagramChange}/>
                </div>
            </div>
            <div className="dx-field photo">
                <div className="dx-field-label">{i18n.t('label.photo')}</div>
                <div className="dx-field-value">
                    <FilePond
                        files={files}
                        allowMultiple={false}
                        onprocessfile={onPhotoProcessed}
                        server={{
                            process: {
                                url: process.env.REACT_APP_API_HOST + `/api/${i18n.language}/upload/image`,
                                headers: {
                                    'Authorization': 'Bearer ' + token.token,
                                    'X-IMAGE-TYPE': 'photo'
                                },
                            }
                        }}
                        name="files"
                        labelIdle='Drag & Drop your files or <span class="filepond--label-action">Browse</span>'
                    />
                    <CheckBox
                        value={showPhotoAtWeb} className="m-2"
                        onValueChanged={handleShowPhotoAtWeb}
                        text={i18n.t('label.show_at_web')}
                    />
                    <CheckBox className="m-2"
                              value={showPhotoAtStream}
                              onValueChanged={handleShowPhotoAtStream}
                              text={i18n.t('label.show_at_stream')}
                    />
                    {photo !== '' && <Col md={{span: 4, offset: 3}}>
                        <span className="position-relative">
                            <span className="text-danger fa fa-times-circle pointer" id="photo" onClick={removeImage}/>
                        </span>
                        <Image id="photo-image" className="thumbnail" style={{width: '100%'}}
                               src={process.env.REACT_APP_API_HOST + `/uploads/avatars/` + encodeURIComponent(photo)}
                               rounded/>
                    </Col>}
                </div>

            </div>
            <div className="dx-field squadLogo">
                <div className="dx-field-label">{i18n.t('label.squad_logo')}</div>
                <div className="dx-field-value">
                    <FilePond
                        allowMultiple={false}
                        onprocessfile={onSquadLogoProcessed}
                        server={{
                            process: {
                                url: process.env.REACT_APP_API_HOST + `/api/${i18n.language}/upload/image`,
                                headers: {
                                    'Authorization': 'Bearer ' + token.token,
                                    'X-IMAGE-TYPE': 'squadLogo'
                                },
                            }
                        }}
                        name="files"
                        labelIdle='Drag & Drop your files or <span class="filepond--label-action">Browse</span>'
                    />
                    <CheckBox className="m-2"
                              value={showSquadLogoAtWeb}
                              onValueChanged={handleShowSquadLogoAtWeb}
                              text={i18n.t('label.show_at_web')}
                    />
                    <CheckBox className="m-2"
                              value={showSquadLogoAtStream}
                              onValueChanged={handleShowSquadLogoAtStream}
                              text={i18n.t('label.show_at_stream')}
                    />
                    {squadLogo !== '' && <Col md={{span: 4, offset: 3}}>
                        <span className="position-relative">
                            <span className="text-danger fa fa-times-circle pointer" id="squadLogo"
                                  onClick={removeImage}/>
                        </span>
                        <Image id="squadLogo-image" className="thumbnail" style={{width: '100%'}}
                               src={process.env.REACT_APP_API_HOST + `/uploads/avatars/` + encodeURIComponent(squadLogo)}
                               rounded/>
                    </Col>}
                </div>
            </div>
            <div className="dx-field">
                <Button onClick={savePilotProfile} type="button" variant="primary" size="lg">
                    {i18n.t('button.save')}
                </Button>
                <Button className="m-4" type="button" onClick={redirectToHome} variant="danger" size="lg">
                    {i18n.t('button.close')}
                </Button>
                {/*{!facebookConnected && <FacebookLogin*/}
                {/*    appId={process.env.REACT_APP_FACEBOOK_APP_ID}*/}
                {/*    fields="name,email,picture"*/}
                {/*    callback={responseFacebook}*/}
                {/*    textButton={i18next.t('button.connect_facebook')}*/}
                {/*    cssClass="btn btn-primary btn-lg"*/}
                {/*    icon="fa-facebook-square fa-lg m-1"*/}
                {/*/>}*/}
            </div>
        </div>
    );
};

export default React.memo(ProfileForm);
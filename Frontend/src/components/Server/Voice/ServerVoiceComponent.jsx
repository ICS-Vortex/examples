import React from 'react';
import i18next from "i18next";

const ServerVoiceComponent = ({server}) => {
    return (
        <div className="voice">
            <div className="voice__head">
                <p><strong> — {i18next.t('message.cleared_for_takeoff')}!</strong></p>
                <p>{i18next.t('label.voice_servers')}</p>
            </div>
            <div className="voice__body">
                <ul className="voice__list">
                    {server.teamSpeakAddress && <li>
                        <a className="text-decoration-none" href={`ts3server://${server.teamSpeakAddress}`}>
                            <figure><img src="/images/teamspeak.png" alt={'TeamSpeak 3'}/></figure>
                            {/*{i18next.t('label.teamspeak')}*/}
                            {server.teamSpeakAddress}
                        </a>
                    </li>}
                    {server.mumbleAddress && <li>
                        <a className="text-decoration-none" href={`mumble://${server.mumbleAddress}`}>
                            <figure><img src="/images/mumble.png" alt={'Mumble'}/></figure>
                            {/*{i18next.t('label.mumble')}*/}
                            {server.mumbleAddress}
                        </a>
                    </li>}
                    {server.discordAddress && <li>
                        <a className="text-decoration-none" href={server.discordAddress}>
                            <figure><img src="/images/discord.png" alt={'Discord'}/></figure>
                            {/*{i18next.t('label.discord')}*/}
                            <span>Discord</span>
                        </a>
                    </li>}
                    {server.srsAddress && <li>
                        <a className="text-decoration-none" href="#">
                            <figure><img src="/images/srs.png" alt={'Simple Radio Standalone'}/></figure>
                            {/*{i18next.t('label.srs')}*/}
                            {server.srsAddress}
                        </a>
                    </li>}
                </ul>
            </div>
        </div>
    );
}

export default ServerVoiceComponent;
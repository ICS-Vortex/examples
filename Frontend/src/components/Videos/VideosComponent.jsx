import React from 'react';
import i18next from '../../i18n';

const VideosComponent = ({server}) => {
    return (
        <div className="video-aside">
            <div className="title-aside">{i18next.t('label.video_and_streaming')}</div>
            <div className="video-aside__list">
                {server.featuredVideos?.map((video, i) => (
                    <div className="video-aside__item" key={i}>
                        <iframe width="100%"
                                src={`https://www.youtube.com/embed/${video.code}`}
                                frameBorder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowFullScreen />
                    </div>
                ))}
            </div>
        </div>
    );
}

export default VideosComponent;
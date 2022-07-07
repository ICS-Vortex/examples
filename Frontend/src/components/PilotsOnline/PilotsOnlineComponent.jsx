import React, {useEffect} from 'react';

const PilotsOnlineComponent = ({server}) => {
    const [flights, setFlights] = React.useState([]);

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/servers/${server.id}/online`)
            .then(r => r.json())
            .then(data => {
                setFlights(data);
            })
        ;
    }, []);
    //
    // const formatTime = (time) => {
    //     if (time < 0) {
    //         return '00:00:00';
    //     }
    //     let sec = time % 60;
    //     time = Math.floor(time / 60);
    //     let min = time % 60;
    //     time = Math.floor(time / 60);
    //     if (sec < 10) {
    //         sec = `0${sec}`;
    //     }
    //     if (min < 10) {
    //         min = `0${min}`;
    //     }
    //     if (time < 10) {
    //         time = `0${time}`;
    //     }
    //     return time + ":" + min + ":" + sec;
    // }
    //
    // const getMemberAvatar = (member: PilotOnline) => {
    //     let avatar;
    //     if (member.pilot) {
    //         avatar = <Avatar alt={member.pilot.callsign.toString()} src="/static/images/avatar/1.jpg"/>;
    //     }
    //     if (!member.pilot.avatar && member.pilot.side === 'SPECTATOR') {
    //         avatar = <Avatar alt={member.pilot.callsign.toString()} src="/images/slide1.png"/>;
    //     }
    //
    //     if (!member.pilot.avatar && member.pilot.side === 'RED') {
    //         avatar = <Avatar alt={member.pilot.callsign.toString()} src={process.env.REACT_APP_API_HOST
    //         + '/images/planes/' + member.pilot.plane.toLowerCase() + '.png'}/>;
    //     }
    //
    //     if (!member.pilot.avatar && member.pilot.side === 'BLUE') {
    //         avatar = <Avatar alt={member.pilot.callsign.toString()} src={process.env.REACT_APP_API_HOST + '/images/planes/'
    //         + member.pilot.plane.toLowerCase() + '.png'}/>;
    //     }
    //
    //     return <span title={!member.pilot.plane ? member.pilot.side : member.pilot.plane}>{avatar}</span>;
    // }
    //
    // const formatFrequencies = (frequencies) => {
    //     if (frequencies === null || frequencies === undefined) {
    //         return <span title="No radio data">
    //             <IconButton aria-label="delete">
    //                 <MicOff className="text-default"/>
    //             </IconButton>
    //         </span>;
    //     }
    //
    //     let freq = [];
    //     for (let i = 0; i < frequencies.length; i++) {
    //         freq.push((frequencies[i].frequency / 1000000).toFixed(3) + 'MHz');
    //     }
    //     return <span title={freq.join(' | ')}>
    //         <IconButton aria-label="delete">
    //             <HeadsetMicSharp className="text-info"/>
    //         </IconButton>
    //     </span>;
    // }

    const getFlightPlaneImage = (online) => {
        if (online.plane) {
            return <div title={online.plane.name}>
                <img width={50}
                     src={`${process.env.REACT_APP_API_HOST}/images/planes/${online.plane.name?.toLowerCase()}.png`}
                     alt=""/>
            </div>
        }
    };

    const getPilotCallsign = (online) => {
        let color;
        switch (online.side) {
            case 'RED':
                color = 'text-danger';
                break;
            case 'BLUE':
                color = 'text-blue';
                break;
            case 'SPECTATOR':
        }
        return <div className={color}>
            {online.pilot?.ipCountry !== null && online.pilot?.ipCountry !== undefined &&
            <span className={'flag-icon flag-icon-' + online.pilot?.ipCountry.toString().toLowerCase()}/>
            }
            {online.pilot?.username}
        </div>;
    }

    return (
        <React.Fragment>
            {/*<PanelComponent>*/}
            {/*    Pilots online*/}
            {/*</PanelComponent>*/}
            {/*<TableContainer component={Paper}>*/}
            {/*    <Table aria-label="simple table">*/}
            {/*        <TableHead>*/}
            {/*            <TableRow>*/}
            {/*                <TableCell>*/}
            {/*                <span title={i18next.t('label.callsign')}>*/}
            {/*                    <AccountCircleRounded/>*/}
            {/*                </span>*/}
            {/*                </TableCell>*/}
            {/*                <TableCell>*/}
            {/*                    <span title={i18next.t('label.plane')}><AirplanemodeActiveRounded/></span>*/}
            {/*                </TableCell>*/}
            {/*            </TableRow>*/}
            {/*        </TableHead>*/}
            {/*        <TableBody>*/}
            {/*            {server.pilotsOnline && server.pilotsOnline.length > 0 && server.pilotsOnline.map(online => (*/}
            {/*                <TableRow key={online.id}>*/}
            {/*                    <TableCell>{getPilotCallsign(online)}</TableCell>*/}
            {/*                    <TableCell>{getFlightPlaneImage(online)}</TableCell>*/}
            {/*                </TableRow>*/}
            {/*            ))}*/}
            {/*        </TableBody>*/}
            {/*    </Table>*/}
            {/*</TableContainer>*/}
        </React.Fragment>
    );
}

export default PilotsOnlineComponent;

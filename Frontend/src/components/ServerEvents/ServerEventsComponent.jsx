import React, {Component} from 'react';
import IEventMessage from "../../interfaces/IEventMessage";
import PerfectScrollbar from "react-perfect-scrollbar";
import ReactHtmlParser from "react-html-parser";

interface IProps {
    messages: IEventMessage[];
    connected: boolean;
}

class ServerEventsComponent extends Component {
    getAvatarForEvent(message: IEventMessage) {
        switch (message.event) {
            // case 'start':
            //     return <Avatar className="bg-info text-white"><Icons.ScreenShare/></Avatar>
            // case 'stop':
            //     return <Avatar className=""><Icons.StopScreenShare/></Avatar>
            // case 'enter':
            //     return <Avatar className="bg-info text-white"><Icons.MeetingRoom/></Avatar>
            // case 'join':
            //     return <Avatar className={`bg-${message.color} text-white`}><Icons.Flight/></Avatar>
            // case 'takeoff':
            //     return <Avatar className={`bg-${message.color} text-white`}><Icons.FlightTakeoff/></Avatar>
            // case 'land':
            //     return <Avatar className={`bg-${message.color} text-white`}><Icons.FlightLand/></Avatar>
            // case 'dead':
            //     return <Avatar className={`bg-${message.color} text-white`}><Icons.LocalHospital/></Avatar>
            // case 'crash':
            //     return <Avatar className={`bg-${message.color} text-white`}><Icons.Warning/></Avatar>
            // case 'eject':
            //     return <Avatar className={`bg-${message.color} text-white`}><Icons.EventSeat/></Avatar>
            // case 'left':
            //     return <Avatar className="bg-warning"><Icons.NoMeetingRoom/></Avatar>
            // case 'kill':
            //     return <Avatar className={`bg-${message.color} text-white`}><Icons.Stars/></Avatar>
            // case 'won':
            //     return <Avatar><Icons.StarHalf/></Avatar>
            // case 'friendfire':
            //     return <Avatar className={`bg-danger text-white`}><Icons.LocalHospital/></Avatar>
            default:
                return <span/>
        }
    }

    render() {
        const listStyle = {height: 300, width: '100%', overflowY: 'scroll'};

        return (
            <>
                {/*<PanelComponent>*/}
                {/*    Events {this.props.connected ?*/}
                {/*    <Tooltip style={{float: 'right'}} title="Notifications enabled"><EventAvailable*/}
                {/*        className="text-info"/></Tooltip> :*/}
                {/*    <Tooltip style={{float: 'right'}} title="Notifications disabled"><Warning*/}
                {/*        color="error"/></Tooltip>}*/}
                {/*</PanelComponent>*/}
                {/*<Card>*/}
                {/*    <CardActionArea>*/}
                {/*        <CardContent style={listStyle}>*/}
                {/*            <PerfectScrollbar>*/}
                {/*                <List>*/}
                {/*                    {this.props.messages!.map((message: IEventMessage, i) => (*/}
                {/*                        <ListItem key={i}>*/}
                {/*                            <ListItemAvatar>*/}
                {/*                                {this.getAvatarForEvent(message)}*/}
                {/*                            </ListItemAvatar>*/}
                {/*                            <ListItemText style={{fontSize: 5}}*/}
                {/*                                          primary={ReactHtmlParser(message.message)}/>*/}
                {/*                        </ListItem>*/}
                {/*                    ))}*/}
                {/*                </List>*/}
                {/*            </PerfectScrollbar>*/}
                {/*        </CardContent>*/}
                {/*    </CardActionArea>*/}
                {/*</Card>*/}
            </>
        );
    }
}

export default ServerEventsComponent;
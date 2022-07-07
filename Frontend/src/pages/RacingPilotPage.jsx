import React, {useEffect} from "react";

const RacingPilotPage = (props) => {
    const id = parseInt(props.match.params.id);
    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/racing/pilot/${id}`)
            .then(r => r.json())
            .then(data => {
                // console.log(data.server);

            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, []);
    return <React.Fragment>

    </React.Fragment>;
};

export default RacingPilotPage;
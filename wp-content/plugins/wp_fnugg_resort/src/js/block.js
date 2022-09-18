import axios from 'axios';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { RichText } = wp.editor;
const apiFetch = wp.apiFetch;

var searchValue;

registerBlockType(
    'gutenberg-block/fnugg-block', 
    {
	    title: 'Fnugg Resort', 
	    icon: 'palmtree',
	    category: 'widgets',
        attributes: {
            searchName: {
                type: 'string',
            },
            placeHolder: {
                type: 'string'
            },
        },
        edit ( props ) {
            const AutocompleteAPI = [
                {
                    name: "AutocompleteAPI",
                    triggerPrefix: "/",
                    options: async function (search) {
                        let payload = '';
                        let responseArray = [];
                        if ( search ) {
                            payload = '?q=' + encodeURIComponent( search );
                        }
                        let results = await axios.get('https://api.fnugg.no/suggest/autocomplete/' + payload);
                        if(results.data.result[0] == undefined) return [{name: 'Nothing here :('}];
                        if(results.data.result['0'].nearby){
                            var exception = {};
                            exception = Object.assign(exception, results.data.result['0'])
                            delete exception.nearby;
                            responseArray.push(exception);
                            responseArray = responseArray.concat(results.data.result['0'].nearby);
                        }else{
                            responseArray = results.data.result;
                        }
                        return responseArray;
                    },
                    isDebounced: true,
                    getOptionKeywords( option ) {
                        return [option.name];
                    },
                    getOptionLabel: option => {
                        return (
                        <span>
                          <span className="icon" ></span>{ option.name }
                        </span>
                      )
                    },
                    getOptionCompletion( option ) {
                        if(option.name == 'Nothing here :(') return '/';
                        return `/${ option.name }`;
                    },
                }
            ];
            
            var placeHolder = props.attributes.placeHolder;
                       
            function onChangeSearch ( content ) {
                props.setAttributes({searchName: content.split(" ")[0], placeHolder: content})
                searchValue = content;
            }          
              
            return (
                <div id="block-dynamic-box" style={{ maxWidth: '350px', }}> 
                    <h4 style={{ marginBottom: '30px', }}>Fnugg resort block</h4>
                    <p>Please start typing a resorts name with "/" in the field below and then choose the resort from the options.</p>
                    <p>Or just enter first word of the resort name.</p>
                    <label>Resort name:</label>
                    <RichText
                        autocompleters={ AutocompleteAPI }
                        onChange={onChangeSearch}
                        value={searchValue}
                        placeholder={props.attributes.placeHolder ? props.attributes.placeHolder : 'Knaben Alpinsenter'}
                    />              
                </div>
            )
         },
        save ( props ) {
            return null
        },
    } 
);
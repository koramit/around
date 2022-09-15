import{_ as C,s as h,r as D,E as k,o as t,c as n,F as _,b as m,a as l,i as g,w as P,T as S,d as j,v as x,t as w,q as v,p as A,j as L}from"./app.e266bcee.js";import{d as I}from"./debounce.7c98259e.js";import{_ as q}from"./FormRadio.75a5594a.js";import{I as N}from"./IconLine.f53d611c.js";import{_ as b}from"./FormCheckbox.7aed38ca.js";const c=s=>(A("data-v-653411de"),s=s(),L(),s),F=c(()=>l("h2",{class:"form-label"}," Preferences ",-1)),R=c(()=>l("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement",id:"line-application"}," line application ",-1)),T=c(()=>l("hr",{class:"my-4 border-b border-accent"},null,-1)),K=c(()=>l("p",{class:"font-medium italic text-complement"}," Collect email address from LINE account ",-1)),$=c(()=>l("p",{class:"mt-2 md:mt-4"}," So we can send you less importance notifications to this email, totally optional. ",-1)),M=["href"],Y=j(" LINK "),z={key:1,class:"px-2 py-1 md:px-4 md:py-2 bg-accent text-white rounded-3xl italic"},G=["href"],H=j(" ADD FRIEND "),J={key:3,class:"px-2 py-1 md:px-4 md:py-2 bg-line-app text-white rounded-3xl italic"},Q=c(()=>l("hr",{class:"my-4 border-b border-dashed"},null,-1)),W=c(()=>l("h2",{class:"mt-6 md:mt-12 xl:mt-24 form-label italic text-xl text-complement",id:"notifications"}," notifications ",-1)),X=c(()=>l("hr",{class:"my-4 border-b border-accent"},null,-1)),Z={key:0},ee=c(()=>l("h3",{class:"mt-4 mb-2 md:mt-8 md:mb-4 font-medium text-complement"}," Events ",-1)),te={class:"p-2 md:p-4 border-l-2 border-accent space-y-2 md:space-y-4"},se={class:"italic"},le=c(()=>l("h3",{class:"mt-4 mb-2 md:mt-8 md:mb-4 font-medium text-complement"}," Channels ",-1)),ne={key:0,class:"p-2 md:p-4 border-l-2 border-accent space-y-2 md:space-y-4"},oe={class:"italic"},ae={__name:"PreferencePage",props:{preferences:{type:Object,required:!0},configs:{type:Object,required:!0}},setup(s){const p=s,V=h({line_email_consent:"accepted"}),E=h({lineEmailConsentOptions:[{value:"accepted",label:"Yes please"},{value:"declined",label:"NO, I'm good"}]}),O=D(null),i=h({...p.preferences.notification}),f=h({...p.configs.event_based_notifications}),u=h({...p.configs.subscribed_channels});k([()=>f,()=>u],()=>U(),{deep:!0});const U=I(()=>{var e,a;let y=(e=Object.keys(f).map(d=>f[d].filter(r=>r.subscribed).map(r=>r.id))[0])!=null?e:[],o=(a=Object.keys(u).map(d=>u[d].filter(r=>r.subscribed).map(r=>r.id))[0])!=null?a:[];window.axios.patch(p.configs.routes.update,{subscriptions:y.concat(o)}).catch(d=>console.log(d))},1500);k(()=>i,()=>B(),{deep:!0});const B=I(()=>{window.axios.patch(p.configs.routes.update,{notification:{...i}}).catch(y=>console.log(y))},1500);return(y,o)=>(t(),n(_,null,[F,R,T,s.configs.can.link_line&&s.configs.routes.link_line?(t(),n(_,{key:0},[K,$,m(q,{class:"mt-4 md:mt-8 md:w-1/2 md:grid grid-cols-2 gap-x-4",name:"line_email_consent",modelValue:V.line_email_consent,"onUpdate:modelValue":o[0]||(o[0]=e=>V.line_email_consent=e),options:E.lineEmailConsentOptions},null,8,["modelValue","options"]),l("a",{class:"flex justify-center items-center gap-x-2 btn btn-accent bg-line w-full md:w-1/2 mt-4 md:mt-8",href:s.configs.routes.link_line+"?email_consent="+V.line_email_consent},[m(N,{class:"w-6 h-6 text-white"}),Y],8,M)],64)):s.configs.routes.link_line&&!s.configs.friends.line?(t(),n("span",z,"LINKED")):g("",!0),s.configs.can.add_line&&s.configs.routes.add_line?(t(),n("a",{key:2,class:"flex justify-center items-center gap-x-2 btn btn-accent bg-line w-full md:w-1/2 mt-4 md:mt-8",href:s.configs.routes.add_line,target:"_blank",ref_key:"lineAddButton",ref:O},[m(N,{class:"w-6 h-6 text-white"}),H],8,G)):s.configs.friends.line?(t(),n("span",J,"FRIENDED")):g("",!0),Q,W,X,m(b,{label:"Mute",name:"mute",class:"my-2 md:my-4",toggler:!0,modelValue:i.mute,"onUpdate:modelValue":o[1]||(o[1]=e=>i.mute=e)},null,8,["modelValue"]),m(S,{name:"slide-fade"},{default:P(()=>[i.mute?g("",!0):(t(),n("div",Z,[ee,m(b,{class:"my-2 md:my-4",label:"Request approval updates",name:"notify_approval_result",modelValue:i.notify_approval_result,"onUpdate:modelValue":o[2]||(o[2]=e=>i.notify_approval_result=e),toggler:!0},null,8,["modelValue"]),l("section",te,[(t(!0),n(_,null,x(Object.keys(f),e=>(t(),n("div",{key:e},[l("label",se,w(e)+" :",1),(t(!0),n(_,null,x(f[e],a=>(t(),v(b,{key:a.id,label:a.label,toggler:!0,modelValue:a.subscribed,"onUpdate:modelValue":d=>a.subscribed=d,class:"mt-2 md:mt-4"},null,8,["label","modelValue","onUpdate:modelValue"]))),128))]))),128))]),le,m(b,{class:"my-2 md:my-4",label:"Auto subscribe to channel I create",name:"auto_subscribe_to_channel",modelValue:i.auto_subscribe_to_channel,"onUpdate:modelValue":o[3]||(o[3]=e=>i.auto_subscribe_to_channel=e),toggler:!0},null,8,["modelValue"]),m(b,{class:"my-2 md:my-4",label:"Auto unsubscribe from an inactive channel",name:"auto_unsubscribe_to_channel",modelValue:i.auto_unsubscribe_to_channel,"onUpdate:modelValue":o[4]||(o[4]=e=>i.auto_unsubscribe_to_channel=e),toggler:!0},null,8,["modelValue"]),Object.keys(u).length?(t(),n("section",ne,[(t(!0),n(_,null,x(Object.keys(u),e=>(t(),n("div",{key:e},[l("label",oe,w(e)+" :",1),(t(!0),n(_,null,x(u[e],a=>(t(),v(b,{key:a.id,label:a.label,toggler:!0,modelValue:a.subscribed,"onUpdate:modelValue":d=>a.subscribed=d,class:"mt-2 md:mt-4"},null,8,["label","modelValue","onUpdate:modelValue"]))),128))]))),128))])):g("",!0)]))]),_:1})],64))}};var ue=C(ae,[["__scopeId","data-v-653411de"]]);export{ue as default};
# use the imagor base image
FROM shumc/imagor:1.5.11

USER root

# Install xz-utils before extracting
RUN apt-get update && apt-get install -y xz-utils && rm -rf /var/lib/apt/lists/*

# add the s6 overlay
ARG S6_OVERLAY_VERSION=3.2.1.0
ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-noarch.tar.xz /tmp
RUN tar -C / -Jxpf /tmp/s6-overlay-noarch.tar.xz
ADD https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-x86_64.tar.xz /tmp
RUN tar -C / -Jxpf /tmp/s6-overlay-x86_64.tar.xz

# add supercronic
ADD https://github.com/aptible/supercronic/releases/download/v0.2.34/supercronic-linux-amd64 /tmp
RUN echo "e8631edc1775000d119b70fd40339a7238eece14 /tmp/supercronic-linux-amd64" | sha1sum -c - && \
    chmod +x /tmp/supercronic-linux-amd64 && \
    mv /tmp/supercronic-linux-amd64 /usr/local/bin/supercronic

# Copy s6 service configurations
COPY services/ /etc/s6-overlay/s6-rc.d

# Copy crontab
COPY crontab /etc/crontab

ENTRYPOINT ["/init"]
